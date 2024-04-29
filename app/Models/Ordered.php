<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $order
 */
trait Ordered {

    protected static $_orderingHooksDisabled = false;

    protected static function bootOrdered() {

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });

        $getCategoryQuery = function (Model $me) {
            $query = self::query();
            if (property_exists(self::class, 'orderedCategory')) {
                $ocs = self::$orderedCategory;
                if (!is_array($ocs)) {
                    $ocs = [$ocs];
                }
                foreach ($ocs as $oc) {
                    $query->where($oc, '=', $me->{$oc});
                }
            }
            return $query;
        };

        self::creating(function (Model $me) use ($getCategoryQuery) {
            /** @var Model $me */
            if (empty($me->order)) {
                $me->order = $getCategoryQuery($me)->max('order') + 1;
            }
        });

        self::created(function (Model $me){
            if (self::$_orderingHooksDisabled) return;
            self::fixOrders($me->id);
        });

        self::updated(function (Model $me) use ($getCategoryQuery) {
            if (self::$_orderingHooksDisabled) return;

            $categoryChanged =
                property_exists(self::class, 'orderedCategory')
                && $me->isDirty(self::$orderedCategory);

            if ($me->isDirty('order') && !$categoryChanged) {
                if ($me->getOriginal('order') < $me->order) {
                    $toUpdate = $getCategoryQuery($me)
                        ->where('order', '<=', $me->order)
                        ->where('order', '>', $me->getOriginal('order'))
                        ->where('id', '!=', $me->id)->get();
                    self::$_orderingHooksDisabled = true;
                    foreach ($toUpdate as $item) {
                        $item->update(['order' => $item->order - 1]);
                    }
                    self::$_orderingHooksDisabled = false;
                }
                self::fixOrders($me->id);
            } else if($me->isDirty('order')){
                self::fixOrders($me->id);
            } else if ($categoryChanged) {
                self::fixOrders($me->id);
            }
        });

        $isSoftDeletes = in_array(
            SoftDeletes::class,
            array_keys((new \ReflectionClass(self::class))->getTraits())
        );

        self::deleted(function(){
            if (self::$_orderingHooksDisabled) return;
            self::fixOrders();
        });

        if($isSoftDeletes){
            self::softDeleted(function($me){
                if (self::$_orderingHooksDisabled) return;
                self::fixOrders();
            });
            self::restored(function($me){
                if (self::$_orderingHooksDisabled) return;
                self::fixOrders($me->id);
            });
        }


    }

    public function fixOrder() {
        self::fixOrders($this->id);
    }

    public static function fixOrders($preserveId = null) {
        if(self::$_orderingHooksDisabled) return;
        $orderPart = [];
        if (property_exists(self::class, 'orderedCategory')) {
            $ocs = self::$orderedCategory;
            if (!is_array($ocs)) {
                $ocs = [$ocs];
            }
            $partitionPart = 'partition by ' . join(', ', array_map(fn($itm) => '`' . $itm . '`', self::$orderedCategory));

            foreach ($ocs as $oc) {
                $orderPart[] = '`' . $oc . '` ASC';
            }
        } else {
            $partitionPart = '';
        }

        $orderPart[] = '`order` ASC';
        if ($preserveId) {
            $orderPart[] = '`id` = ' . $preserveId . ' DESC';
        }
        $orderPart[] = '`id` ASC';
        $orderPart = join(', ', $orderPart);

        if (in_array(SoftDeletes::class, class_uses(self::class))) {
            $wherePart = ' WHERE deleted_at is NULL';
        } else {
            $wherePart = '';
        }

        $query = 'SELECT `id`, `rank` as `order` FROM (
            SELECT `id`, `order`,
                ROW_NUMBER() over (' . $partitionPart . ' ORDER BY ' . $orderPart . ') as `rank`
            FROM `' . (new self())->getTable() . '` ' . $wherePart . ') t
		WHERE `order` != `rank`';

        $results = \DB::select($query);
        self::$_orderingHooksDisabled = true;
        foreach ($results as $result) {
            self::find($result->id)->update(['order' => $result->order]);
        }
        self::$_orderingHooksDisabled = false;
    }


}
