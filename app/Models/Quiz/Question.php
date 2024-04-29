<?php

namespace App\Models\Quiz;

use App\Models\Ordered;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $block_id
 * @property int $ticket
 * @property int $order
 * @property string $text
 * @property string $description
 * @property array  $options
 * @property int    $right
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *
 * @property-read Block $block;
 *
 * @mixin \Eloquent
 */
class Question extends Model {
    use HasFactory, SoftDeletes, Ordered;

    protected $table = 'quiz_questions';

    protected $fillable = ['block_id', 'ticket', 'order', 'text', 'description', 'options', 'right', 'created_at', 'updated_at'];

    protected $casts = [
        'order' => 'integer',
        'options' => 'array',
        'right' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected static $orderedCategory = ['block_id', 'ticket'];

    public function block(){
        return $this->belongsTo(Block::class);
    }


}
