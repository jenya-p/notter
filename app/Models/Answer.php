<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 * @property int $test_id
 * @property int $variant_id
 * @property string $ticket_index
 * @property string $question_index
 * @property string $question_text
 * @property string $answer_text
 * @property boolean $is_right
 * @property string $right_answer_text
 * @property string $question_description
 * @property string $test_answers
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Variant $variant
 * @property-read Question $question
 * @property-read Test $test
 *
 * @mixin \Eloquent
 */
class Answer extends Model {
    use HasFactory;

    protected $table = 'test_answers';

    protected $fillable = ['test_id','question_id', 'variant_id',
        'ticket_index','question_index','question_text','answer_text','is_right','right_answer_text',
        'question_description','test_answers'
    ];

    protected $casts = [
        'is_right' => 'boolean',
        'ticket_index' => 'integer',
        'question_index' => 'integer'
    ];

    public function test(){
        return $this->belongsTo(Test::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function variant(){
        return $this->belongsTo(Variant::class);
    }

}
