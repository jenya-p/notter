<?php

namespace App\Models\Test;

use App\Models\Payment;
use App\Models\Quiz\Block;
use App\Models\Quiz\Question;
use App\Models\Test\Question as TestQuestion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 * @property int $user_id
 * @property int $block_id
 * @property int $payment_id
 * @property string $title
 * @property float $amount
 * @property Carbon $available_till
 * @property int $passing_score
 *
 * @property int $ticket_count
 * @property int $ticket_passed_count
 * @property int $ticket_failed_count
 * @property int $question_count
 * @property int $question_right_count
 * @property int $question_wrong_count
 *
 * @property Carbon $completed_at
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read int $question_skipped_count
 * @property-read string $status
 * @property-read string $status_name
 *
 * @property-read User $user
 * @property-read Block $block
 * @property-read Ticket[] $tickets
 * @property-read Payment $payment
 *
 * @method static Builder my()
 * @method static Builder active()
 *
 * @mixin \Eloquent
 */
class Test extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'tests';

    const STATUS_DONE = 'done';
    const STATUS_ACTIVE = 'active';
    const STATUS_FINISHED = 'finished';
    const STATUS_UNAVAILABLE='unavailable';

    const STATUSES = [
        self::STATUS_DONE => 'Пройден',
        self::STATUS_ACTIVE => 'Активный',
        self::STATUS_FINISHED => 'Завершен',
        self::STATUS_UNAVAILABLE => 'Недоступно'
    ];

    protected $fillable = [
        'user_id','block_id','payment_id','title','available_till','amount','passing_score',
        'question_count','right_count','wrong_count', 'completed_at',
        'created_at','updated_at',
    ];

    protected $appends = ['status', 'question_skipped_count'];

    protected $casts = [
        'order' => 'integer',
        'available_till' => 'date:Y-m-d',
        'amount' => 'float',
        'passing_score' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function block(){
        return $this->belongsTo(Block::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class)->orderBy('order');
    }

    public function questions(){
        return $this->hasManyThrough(TestQuestion::class, Ticket::class);
    }

    public function payment(){
        return $this->belongsTo(Payment::class);
    }


    public function getStatusAttribute(){
        if (!empty($this->completed_at)){
            return self::STATUS_DONE;
        } else if(empty($this->available_till)){
            return self::STATUS_UNAVAILABLE;
        } else if(
            $this->available_till->endOfDay()->lessThanOrEqualTo(now())
        ){
            return self::STATUS_FINISHED;
        } else {
            return self::STATUS_ACTIVE;
        }
    }

    public function getStatusNameAttribute(){
        if(array_key_exists($this->status, self::STATUSES)){
            return self::STATUSES[$this->status];
        } else {
            return $this->status;
        }
    }


    public function getQuestionSkippedCountAttribute(){
        return $this->question_count - $this->question_right_count - $this->question_wrong_count;
    }

    public function updateCounters(){
        $this->ticket_count = $this->tickets()->count();
        $this->question_count = $this->questions()->count();
        $this->ticket_failed_count = 0;
        $this->ticket_passed_count = 0;
        $tickets = $this->tickets()->whereNotNull('completed_at')->get();
        /** @var Ticket $ticket */
        foreach ($tickets as $ticket){
            if($this->passing_score == null){
                $count = $ticket->questions()->whereRaw('(`right` != `answer` or `answer` is null)')->count();
                if($count == 0){
                    $this->ticket_passed_count++;
                } else {
                    $this->ticket_failed_count++;
                }
            } else {
                $count = $ticket->questions()->whereRaw('(`right` = `answer` and not `answer` is null)')->count();
                if($count >= $this->passing_score){
                    $this->ticket_passed_count++;
                } else {
                    $this->ticket_failed_count++;
                }
            }
        }

        $this->question_right_count = $this->questions()->whereRaw('`right` = `answer`')->whereNotNull('answer')->count();
        $this->question_wrong_count = $this->questions()->whereRaw('`right` != `answer`')->whereNotNull('answer')->count();
        $this->save();
    }

    public function isOutdated(){

    }

    public function isAvailable(){
        return \Auth::id() == $this->user_id && !$this->available_till->endOfDay()->lessThanOrEqualTo(now());
    }

    /**
     * Тесты текущего пользователя
     */
    public static function scopeMy(Builder $query){

        if(!empty(\Auth::id())){
            return $query->where('user_id', '=', \Auth::id());
        } else {
            return $query->whereRaw('FALSE');
        }
    }


    /**
     * Тесты доступные в данный момент для прохождения
     */
    public static function scopeActive(Builder $query){
        $query->where('available_till', '>=', now())->whereNull('completed_at');
    }


}
