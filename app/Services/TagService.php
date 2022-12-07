<?php

namespace App\Services;

use App\Models\Tag;
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
     * タグをIdによって取得
     *
     * @return Tag
     */
    public function getTagById($tag_id)
    {
        return $this->tagRepository->getById($tag_id);
    }

    /**
     * タグをアニメによってタグレビューとユーザーとともに取得
     *
     * @return Collection<int,Tag>
     */
    public function getTagsByAnimeWithTagReviewsAndUser($anime)
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

    /**
     * tag_nameからタグIDをapiのために取得
     *
     * @param string $tag_name
     * @return int
     */
    public function getTagIdForApiByName($tag_name)
    {
        return $this->tagRepository->getIdForApiByName($tag_name)->id ?? '登録なし';
    }
}
