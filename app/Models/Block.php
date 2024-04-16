<?php

namespace App\Models;

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
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Ticket[] $tickets
 * @property-read Test[] $tests
 *
 * @method static active();
 *
 * @mixin \Eloquent
 */
class Block extends Model {
    use HasFactory, SoftDeletes, Orderable;

    protected $table = 'quiz_blocks';

    protected $fillable = ['title', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float'
    ];

    public function tickets(){
        return $this->hasMany(Ticket::class)->orderBy('order');
    }

    public function tests(){
        return $this->hasMany(Test::class);
    }


    public function scopeActive(Builder $query){
        return $query->where('active', '=', 1);
    }

}
