<?php

namespace App\Models;

use App\Models\Backfeed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Attachment
 * @package App
 *
 * @property integer $id
 * @property string $name
 * @property string $file
 * @property string $item_type
 * @property integer $item_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Model $parent
 * @property-read string $downloadUrl
 * @property-read string $thumbUrl
 * @property-read string $ext
 * @property-read string $base_name
 *
 * @method static belongsToItem($itemType, $itemId)
 *
 * @mixin \Eloquent
 */
class Attachment extends Model {

    use SoftDeletes, HasTimestamps;

    protected $table = 'attachments';
    protected $primaryKey = 'id';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];


    protected $fillable = [
        'name', 'file', 'item_type', 'item_id', 'created_at', 'updated_at', 'deleted_at',
    ];

    protected $hidden = ['file'];
    protected $appends = ['download_url', 'thumb_url', 'ext', 'base_name'];

    const ITEM_TYPE_BACKFEED = 'backfeed';

    const ITEM_CLASSES = [
        self::ITEM_TYPE_BACKFEED => Backfeed::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent() {
        $itemClass = self::ITEM_CLASSES[$this->item_type];
        return $this->hasOne($itemClass, 'id', 'item_id');
    }

    public function getDownloadUrlAttribute() {
        return route('attachment.download', $this);
    }

    public function getThumbUrlAttribute() {
        $ext = pathinfo($this->file, PATHINFO_EXTENSION);
        if (in_array($ext, ['jpeg', 'jpg', 'png', 'bmp'])) {
            return route('attachment.thumb', $this);
        } else if (in_array($ext, ['doc', 'docx'])) {
            return '/images/file_types/doc.svg';
        } else if (in_array($ext, ['xls', 'xlsx'])) {
            return '/images/file_types/excel.svg';
        } else if (in_array($ext, ['pdf'])) {
            return '/images/file_types/pdf.svg';
        } else {
            return '/images/file_types/file.svg';
        }
    }

    public function getExtAttribute() {
        return \File::extension($this->file);
    }

    public function getBaseNameAttribute() {
        $p = mb_strrpos($this->name, '.');
        if ($p === false) {
            return $this->name;
        } else {
            return mb_substr($this->name, 0, $p);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $itemType
     * @param int $itemId
     */
    public function scopeBelongsToItem($query, $itemType, $itemId) {
        $query
            ->where('item_type', '=', $itemType)
            ->where('item_id', '=', $itemId)
            ->orderBy('id', 'asc');
    }

    public static function updateItemId($ids, $itemId) {
        if (is_numeric($ids)) {
            $ids = [$ids];
        };
        if (!is_array($ids)) return;
        foreach ($ids as $id) {
            if (is_array($id) && array_key_exists('id', $id)) {
                $id = $id['id'];
            }

            /** @var self $item */
            $item = self::find(['id' => $id])->first();

            if (!empty($item)) {
                $item->item_id = $itemId;
                $item->save();
            }
        }

    }

    public function getTrashItemDescription() {
        $ret = "Файл. " . $this->name;
        $parent = $this->parent()->withTrashed()->first();
        if (!empty($parent) && method_exists($parent, 'getTrashItemDescription')) {
            $ret = [$ret, $parent->getTrashItemDescription()];
        }
        return $ret;
    }

    public function isImage() {
        return in_array($this->ext, ['jpeg', 'jpg', 'png', 'bmp']);
    }


}
