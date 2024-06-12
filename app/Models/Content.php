<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $url
 *
 * @property string $title
 * @property string $title_en
 * @property string $content
 * @property string $content_en
 * @property string $seo_title
 * @property string $seo_title_en
 * @property string $seo_description
 * @property string $seo_description_en
 * @property string $seo_keywords
 * @property string $seo_keywords_en
 *
 * @mixin \Eloquent
 */
class Content extends Model
{
    protected $table = 'content';
    public $timestamps = false;
    protected $casts = ['id' => 'string'];

    protected $fillable = [
        'url',
        'title',
        'title_en',
        'content',
        'content_en',
        'seo_title',
        'seo_title_en',
        'seo_description',
        'seo_description_en',
        'seo_keywords',
        'seo_keywords_en',
        ];


}
