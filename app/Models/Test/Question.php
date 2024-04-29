<?php

namespace App\Models\Test;

use App\Models\Ordered;
use App\Models\Quiz\Question as QuizQuestion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $quiz_question_id
 *
 * @property string $question
 * @property string $description
 * @property array  $options
 * @property int    $right
 *
 * @property int    $answer

 * @property Carbon $solved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read string  $result
 * @property-read string  $answer_text
 * @property-read string  $right_answer_text
 *
 * @property-read Ticket $ticket
 * @property-read QuizQuestion $quizQuestion
 *
 * @mixin \Eloquent
 */
class Question extends Model {
    use HasFactory, Ordered;

    protected $table = 'test_questions';

    protected $fillable = [
        'ticket_id',
        'quiz_question_id',
        'order',
        'question',
        'description',
        'options',
        'right',
        'answer',
        'solved_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'order' => 'integer',
        'options' => 'array',
        'right' => 'integer',
        'answer' => 'integer',
        'solved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    private static $orderedCategory = 'ticket_id';

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    public function quizQuestion() {
        return $this->belongsTo(Question::class);
    }

    public function getResultAttribute() {
        if ($this->answer !== null) {
            return $this->answer == $this->right ? 'right' : 'wrong';
        }
        else return 'skipped';
    }

    public function getAnswerTextAttribute() {
        if ($this->answer !== null && array_key_exists($this->answer, $this->options)) {
            return $this->options[$this->answer];
        }
    }

    public function getRightAnswerTextAttribute() {
        if ($this->right !== null && array_key_exists($this->right, $this->options)) {
            return $this->options[$this->right];
        }
    }

    public function isAvailable(){
        return $this->ticket->isAvailable();
    }


}
