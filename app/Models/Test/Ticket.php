<?php

namespace App\Models\Test;

use App\Models\Ordered;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $test_id
 * @property int $order
 * @property Carbon $started_at
 * @property Carbon $completed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Test $test;
 * @property-read Question[] $questions;
 *
 * @mixin \Eloquent
 */
class Ticket extends Model {
    use HasFactory, Ordered;

    protected $table = 'test_tickets';

    protected $fillable = [
        'test_id',
        'order',
        'started_at',
        'completed_at',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected static $orderedCategory = 'test_id';

    public function test(){
        return $this->belongsTo(Test::class);
    }

    public function questions(){
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function isAvailable(){
        return $this->completed_at === null && $this->test->isAvailable();
    }


    public function loadInfo($isTestActive = true){
        if($this->completed_at!==null){
            $this->load('questions');
            $this->right_count = 0;
            $this->wrong_count = 0;
            $this->skipped_count = 0;
            $this->question_count = count($this->questions);
            foreach ($this->questions as $question){
                $question->append(['result','answer_text','right_answer_text']);
                if($question->result === 'right'){
                    $this->right_count++;
                } else if($question->result === 'wrong'){
                    $this->wrong_count++;
                } else {
                    $this->skipped_count++;
                }
            }

            if(empty($test->passing_score)){
                $result = $this->right_count >= $this->question_count;
            } else{
                $result = $this->right_count >= $this->passing_score;
            }
            $this->result = $result ? 'passed' : 'failed';
        } else if($isTestActive){
            $this->load('questions:id,ticket_id,order,question,options,answer');
        } else {
            $this->unavailable = true;
            $this->questions = [];
        }
    }

}
