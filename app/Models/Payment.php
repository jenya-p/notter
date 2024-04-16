<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property float $amount
 * @property Carbon $payed_at
 * @property string $method
 * @property string $type
 * @property string $payment_external_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
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

    protected $fillable = ['order', 'text', 'is_right', 'created_at', 'updated_at'];

    protected $casts = ['payed_at' => 'datetime'];

    protected $appends = ['title'];





}
