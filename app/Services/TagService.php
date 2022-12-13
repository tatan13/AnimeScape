<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Anime;
use App\Repositories\TagRepository;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
    private TagRepository $tagRepository;

    /**
     * コンストラクタ
     *
     * @param TagRepository $tagRepository
     * @return void
     */
    public function __construct(
        TagRepository $tagRepository,
    ) {
        $this->tagRepository = $tagRepository;
    }

    /**
     * タグをidによって取得
     *
     * @param int $tag_id
     * @return Tag
     */
    public function getTagById($tag_id)
    {
        return $this->tagRepository->getById($tag_id);
    }

    /**
     * タグをtag_nameによって取得
     *
     * @param string $tag_name
     * @return Tag
     */
    public function getTagByName($tag_name)
    {
        return $this->tagRepository->getByName($tag_name);
    }

    /**
     * タグをidによって取得
     *
     * @param int $tag_id
     * @return Tag
     */
    public function getTagByIdAllowNull($tag_id)
    {
        return $this->tagRepository->getByIdAllowNull($tag_id);
    }

    /**
     * タグをtag_nameによって取得
     *
     * @param string $tag_name
     * @return Tag
     */
    public function getTagByNameAllowNull($tag_name)
    {
        return $this->tagRepository->getByNameAllowNull($tag_name);
    }

    /**
     * タグをIdによってログインユーザーのタグレビューとともに取得
     *
     * @param int $tag_id
     * @return Tag
     */
    public function getTagWithMyTagReviewWithAnime($tag_id)
    {
        return $this->tagRepository->getTagWithMyTagReviewWithAnime($tag_id);
    }

    /**
     * タグをアニメによってタグレビューとユーザーとともに取得
     *
     * @param Anime $anime
     * @return Collection<int,Tag>
     */
    public function getTagsByAnimeWithTagReviewsAndUser(Anime $anime)
    {
        return $this->tagRepository->getTagsByAnimeWithTagReviewsAndUser($anime);
    }

    /**
     * すべてのタグを取得
     *
     * @return Collection<int,Tag> | Collection<null>
     */
    public function getTagAllOldestTagGroupId()
    {
        return $this->tagRepository->getTagAllOldestTagGroupId();
    }
}
