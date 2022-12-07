<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use \Kyslik\ColumnSortable\Sortable;

    public const TYPE_GENRE = 1;
    public const TYPE_CHARACTER = 2;
    public const TYPE_STORY = 3;
    public const TYPE_MUSIC = 4;
    public const TYPE_PICTURE = 5;
    public const TYPE_CAST = 6;
    public const TYPE_OTHER = 7;

    public const SEARCH_COLUMN = 'name';

    public const TAG_GROUP_ID = [
        self::TYPE_GENRE => [ 'label' => 'ジャンル' ],
        self::TYPE_CHARACTER => [ 'label' => 'キャラクター' ],
        self::TYPE_STORY => [ 'label' => 'ストーリー' ],
        self::TYPE_MUSIC => [ 'label' => '音' ],
        self::TYPE_PICTURE => [ 'label' => '作画' ],
        self::TYPE_CAST => [ 'label' => '声優' ],
        self::TYPE_OTHER => [ 'label' => 'その他' ],
    ];

    protected $fillable = [
        'name',
        'explanation',
        'tag_group_id',
        'spoiler',
    ];

    /**
     * タググループIDをラベルに変換
     *
     * @return string
     */
    public function getTagGroupIdLabelAttribute()
    {
        $tag_group_id = $this->attributes['tag_group_id'];

        if (!isset(self::TAG_GROUP_ID[$tag_group_id])) {
            return '';
        }

        return self::TAG_GROUP_ID[$tag_group_id]['label'];
    }

    /**
     * タグレビューを取得
     *
     * @return HasMany
     */
    public function tagReviews()
    {
        return $this->hasMany('App\Models\TagReview');
    }

    /**
     * アニメを取得
     *
     * @return BelongsToMany
     */
    public function animes()
    {
        return $this->belongsToMany('App\Models\Anime', 'tag_reviews', 'tag_id', 'anime_id')
                    ->withTimestamps();
    }
}
