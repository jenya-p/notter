<?php

namespace App\Models;

use App\Models\Quiz\Block;
use App\Models\Test\Test;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property float $amount
 * @property array $items
 * @property Carbon $payed_at
 * @property string $method
 * @property string $type
 * @property string $external_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read string $status_name
 * @property-read string $description
 * @property-read User $user
 * @property-read Test[] $tests
 * @mixin \Eloquent
 */
class Payment extends Model {
    use HasFactory, SoftDeletes;

    const STATUS_NEW = 'new';
    const STATUS_DONE = 'done';
    const STATUS_CANCELED = 'canceled';

    const METHOD_YANDEX = 'yandex';

    const METHOD_TYPE = 'card';

    protected $table = 'payments';


    protected $fillable = [
        'user_id', 'status', 'amount', 'items', 'payed_at', 'method', 'type', 'external_id',
        'created_at', 'updated_at'];

    protected $casts = [
        'payed_at' => 'datetime',
        'items' => 'array'
    ];

    protected static function boot() {
        parent::boot();

        self::creating(function (self $me) {
            if (empty($me->user_id)) {
                $me->user_id = \Auth::id();
            }
            if (empty($me->status)) {
                $me->status = self::STATUS_NEW;
            }
        });
    }

    public function getDescriptionAttribute() {
        return implode(', ', array_map(fn($itm) => $itm['title'], $this->items));
    }

    public function getStatusNameAttribute() {
       switch ($this->status){
           case 'new': return 'Ожидание';
           case 'done': return 'Оплачено';
           case 'canceled': return 'Отклонено';
           default: return $this->status;
       }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tests(){
        return $this->hasMany(Test::class);
    }

    public function cancel() {
        $this->status = self::STATUS_CANCELED;
        $this->save();
    }

    public function done() {
        $this->status = self::STATUS_DONE;
        $this->payed_at = now();
        $items = $this->items;
        foreach ($items as &$item) {
            $block = Block::find($item['id']);
            if (!empty($block)) {
                $test = $block->createTest([
                    'user_id' => $this->user_id,
                    'title' => $block->title,
                    'amount' => $item['amount'],
                    'payment_id' => $this->id,
                    'available_till' => now()->addMonth()
                ]);
                $item['test_id'] = $test->id;
            }
        }
        $this->items = $items;
        $this->save();
    }

}
