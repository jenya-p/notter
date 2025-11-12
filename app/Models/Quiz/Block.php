<?php

namespace App\Models\Quiz;

use App\Models\Ordered;
use App\Models\Test\Test;
use App\Models\Test\Ticket;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string $id
 * @property string $title
 *
 * @property boolean $active
 * @property string $description
 * @property float $price
 * @property boolean $is_plain_text
 * @property int $passing_score
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read int $ticket_count
 * @property-read int $question_count
 * @property-read Carbon $available_till
 *
 * @property-read Question[] $questions
 * @property-read Test[] $tests
 * @property-read Test[] $myActiveTests
 *
 * @method static Builder active();
 *
 * @mixin \Eloquent
 */
class Block extends Model {
    use HasFactory, SoftDeletes, Ordered;

    protected $table = 'quiz_blocks';

    protected $fillable = ['title', 'active', 'description', 'price', 'is_plain_text', 'passing_score',
        'created_at', 'updated_at'];

    protected $casts = [
        'order' => 'integer',
        'price' => 'float',
        'active' => 'boolean',
        'is_plain_text' => 'boolean',
        'passing_score' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function questions() {
        return $this->hasMany(Question::class)->orderBy('ticket')->orderBy('order');
    }

    public function tests() {
        return $this->hasMany(Test::class);
    }

    public function getTicketCountAttribute() {

        if ($this->relationLoaded('questions')) {
            $tickets = [];
            foreach ($this->questions as $question) {
                if (!empty($question->ticket) && !in_array($question->ticket, $tickets)) {
                    $tickets[] = $question->ticket;
                }
            }
            return count($tickets);
        } else {
            return Question::withoutGlobalScope('order')
                ->selectRaw('COUNT(DISTINCT ticket) as count')
                ->where('block_id', '=', $this->id)
                ->whereNotNull('ticket')
                ->value('count');
        }
    }

    public function getQuestionCountAttribute() {
        return $this->questions()->count();
    }

    public function scopeActive(Builder $query) {
        return $query->where('active', '=', 1);
    }

    public function myActiveTests() {
        return $this->tests()->scopes(['my', 'active']);
    }

    public function getAvailableTillAttribute() {
        return $this->myActiveTests()->max('available_till');
    }

    public function createTest($data) {
        /** @var Test $test */
        $test = $this->tests()->create($data);

        if (!$this->is_plain_text) {
            $currentTicket = $this->questions[0]->ticket;
            /** @var Ticket $Ticket */
            $ticket = $test->tickets()->create();

            foreach ($this->questions as $index => $question) {
                if ($currentTicket !== $question->ticket) {
                    $currentTicket = $question->ticket;
                    $ticket = $test->tickets()->create();
                }

                $ticket->questions()->create([
                    'quiz_question_id' => $question->id,
                    'question' => $question->text,
                    'description' => $question->description,
                    'options' => $question->options,
                    'right' => $question->right
                ]);

            }
        }
        $test->updateCounters();
        return $test;
    }

    public function publish(Test $test) {

        $currentTicket = -1;
        $currentTicketOrder = 0;
        $currentQuestionOrder = 0;

        foreach ($this->questions as $question) {
            if ($currentTicket !== $question->ticket) {
                $currentTicket = $question->ticket;
                $currentTicketOrder++;
                $currentQuestionOrder = 0;
            }
            $currentQuestionOrder++;

            /** @var Ticket $ticket */
            $ticket = $test->tickets()->where('order', '=', $currentTicketOrder)->first();
            if(empty($ticket)){
                $ticket = $test->tickets()->create([
                    'order' => $currentTicketOrder,
                ]);
            } else if(!empty($ticket->started_at)){
                continue;
            }

            /** @var \App\Models\Test\Question $testQuestion */
            $testQuestion = $test->questions()->where('quiz_question_id', $question->id)->first();
            if(empty($testQuestion)){
                $test->questions()->create([
                    'ticket_id' => $ticket->id,
                    'order' => $currentQuestionOrder,
                    'quiz_question_id' => $question->id,
                    'question' => $question->text,
                    'description' => $question->description,
                    'options' => $question->options,
                    'right' => $question->right
                ]);
            } else if(!empty($testQuestion->solved_at)){
                continue;
            } else {
                $testQuestion->update([
                    'ticket_id' => $ticket->id,
                    'order' => $currentQuestionOrder,
                    'question' => $question->text,
                    'description' => $question->description,
                    'options' => $question->options,
                    'right' => $question->right
                ]);
            }
        }
        $test->updateCounters();
        return $test;

    }

}
