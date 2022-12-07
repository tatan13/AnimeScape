<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Models\Anime;
use App\Http\Requests\TagReviewRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TagRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return Tag::class;
    }

    /**
     * 名前からタグを取得
     *
     * @param string $tag_name
     * @return Tag | null
     */
    public function getByName($tag_name)
    {
        return Tag::where('name', $tag_name)->first();
    }

    /**
     * タグをアニメによってタグレビューとユーザーとともに取得
     *
     * @return Collection<int,Tag>
     */
    public function getTagsByAnimeWithTagReviewsAndUser($anime)
    {
        return Tag::whereHas('tagReviews', function ($query) use ($anime) {
            $query->where('anime_id', $anime->id);
        })->with('tagReviews', function ($query) use ($anime) {
            $query->with('user')->where('anime_id', $anime->id);
        })->withCount(['tagReviews' => function ($query) use ($anime) {
            $query->where('anime_id', $anime->id);
        }])->latest('tag_reviews_count')->get();
    }

    /**
     * タグ名とタググループIDによってタグを取得または作成
     *
     * @param string $tag_name
     * @param int $tag_group_id
     * @return Tag | null
     */
    public function firstOrCreateTagByNameAndTagGroupId($tag_name, $tag_group_id)
    {
        return Tag::firstOrCreate([
            'name' => $tag_name
        ], [
            'tag_group_id' => $tag_group_id
        ]);
    }

    /**
     * タグをタググループIDで昇順にしてすべて取得
     *
     * @return Collection<int,Tag> | Collection<null>
     */
    public function getTagAllOldestTagGroupId()
    {
        return Tag::oldest('tag_group_id')->get();
    }

    /**
     * 同一タグが既に登録済みか判定
     *
     * @param Anime $anime
     * @param Tag $tag
     * @return bool
     */
    public function isContainMyTagReviews(Anime $anime, Tag $tag)
    {
        return $tag->tagReviews()->where('anime_id', $anime->id)->where('user_id', Auth::user()->id)->exists();
    }

    /**
     * tag_nameからタグIDをapiのために取得
     *
     * @param string $tag_name
     * @return Tag | null
     */
    public function getIdForApiByName($tag_name)
    {
        return Tag::where('name', $tag_name)->first();
    }
}
