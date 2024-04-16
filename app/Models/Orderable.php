<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order
 */
trait Orderable {
    protected static function bootOrderable() {

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });

        self::creating(function (self $me) {
            /** @var Model $me */
            if (empty($me->order)) {
                if(property_exists(self::class, 'orderableCategory')){
                    $me->order = self::where(self::$orderableCategory, '=', $me->{self::$orderableCategory})->max('order') + 1;
                } else {
                    $me->order = self::max('order') + 1;
                }

            }
        });
    }
}
