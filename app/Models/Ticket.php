<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $block_id
 * @property int $order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $title
 *
 * @property-read Block[] $block;
 * @property-read Question[] $questions;
 *
 * @mixin \Eloquent
 */
class Ticket extends Model {
    use HasFactory, SoftDeletes, Orderable;

    protected $table = 'quiz_tickets';

    protected $fillable = ['order', 'created_at', 'updated_at'];

    protected $appends = ['title'];

    protected static $orderableCategory = 'block_id';

    public function getTitleAttribute(){
        return $this->order;
    }

    public function block(){
        return $this->belongsTo(Block::class);
    }

    public function questions(){
        return $this->hasMany(Question::class)->orderBy('order');
    }


}
