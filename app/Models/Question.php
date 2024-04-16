<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $order
 * @property string $text
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read $title
 *
 * @property-read Ticket $ticket;
 * @property-read Variant[] $variants;
 *
 * @mixin \Eloquent
 */
class Question extends Model {
    use HasFactory, SoftDeletes, Orderable;

    protected $table = 'quiz_questions';

    protected $fillable = ['ticket_id', 'order', 'text', 'description', 'created_at', 'updated_at'];

    protected $appends = ['title'];

    protected static $orderableCategory = 'ticket_id';

    public function getTitleAttribute() {
        return $this->order;
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }

    public function variants() {
        return $this->hasMany(Variant::class)->orderBy('order');
    }


}
