<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property string $id
 * @property string $title
 * @property string $content
 *
 * @mixin \Eloquent
 */
class Content extends Model
{
    protected $table = 'content';
    public $timestamps = false;

    protected $fillable = ['id','title','content'];

}
