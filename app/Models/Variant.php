<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $question_id
 * @property int $order
 * @property string $text
 * @property boolean $is_right
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Question $question
 * @mixin \Eloquent
 */
class Variant extends Model {
    use HasFactory, SoftDeletes, Orderable;

    protected $table = 'quiz_variants';

    protected $fillable = ['question_id','order', 'text', 'is_right', 'created_at', 'updated_at'];

    protected $casts = ['is_right' => 'boolean'];

    protected $appends = ['title'];

    protected static $orderableCategory = 'question_id';

    public function getTitleAttribute(){
        return mb_substr("АБВГEЖЗИКЛМНОПРСТУФХЦЧШЩЮЯ",$this->order - 1, 1);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

}
