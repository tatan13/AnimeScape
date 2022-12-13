<?php

namespace App\Services;

use App\Models\TagReview;
use App\Models\Anime;
use App\Models\Tag;
use App\Repositories\TagReviewRepository;
use App\Repositories\TagRepository;
use App\Repositories\AnimeRepository;
use App\Http\Requests\AnimeTagReviewRequest;
use App\Http\Requests\TagReviewRequest;
use Illuminate\Support\Facades\Auth;

class TagReviewService
{
    private TagReviewRepository $tagReviewRepository;
    private TagRepository $tagRepository;
    private AnimeRepository $animeRepository;

    /**
     * コンストラクタ
     *
     * @param TagReviewRepository $tagReviewRepository
     * @param TagRepository $tagRepository
     * @param AnimeRepository $animeRepository
     * @return void
     */
    public function __construct(
        TagReviewRepository $tagReviewRepository,
        TagRepository $tagRepository,
        AnimeRepository $animeRepository
    ) {
        $this->tagReviewRepository = $tagReviewRepository;
        $this->tagRepository = $tagRepository;
        $this->animeRepository = $animeRepository;
    }

    /**
     * ログインユーザーのアニメに紐づくタグレビューを作成または更新
     *
     * @param Anime $anime
     * @param AnimeTagReviewRequest $submit_tag_review
     * @return void
     */
    public function createOrUpdateMyAnimeTagReview(Anime $anime, AnimeTagReviewRequest $submit_tag_review)
    {
        foreach ($submit_tag_review->modify_type as $key => $modify_type) {
            if (is_null($submit_tag_review->name[$key]) || is_null($submit_tag_review->score[$key])) {
                continue;
            }
            if ($modify_type == 'no_change') {
                continue;
            }
            if ($modify_type == 'change') {
                $my_tag_review = $this->tagReviewRepository->getById($submit_tag_review->tag_review_id[$key]);
                $this->tagReviewRepository->updateTagReviewByScoreAndComment(
                    $my_tag_review,
                    $submit_tag_review->score[$key],
                    $submit_tag_review->comment[$key],
                );
            }
            if ($modify_type == 'delete') {
                $this->tagReviewRepository->getById($submit_tag_review->tag_review_id[$key]);
                $this->tagReviewRepository->deleteById($submit_tag_review->tag_review_id[$key]);
            }
            if ($modify_type == 'add') {
                $tag = $this->tagRepository->firstOrCreateTagByNameAndTagGroupId(
                    $submit_tag_review->name[$key],
                    $submit_tag_review->tag_group_id[$key]
                );
                if ($this->isContainMyTagReviews($anime, $tag)) {
                    continue;
                }
                $this->tagReviewRepository->createByAnimeTagReviewRequest([
                    'anime_id' => $anime->id,
                    'user_id' => Auth::user()->id,
                    'tag_id' => $tag->id,
                    'score' => $submit_tag_review->score[$key],
                    'comment' => $submit_tag_review->comment[$key],
                ]);
            }
        }
    }

    /**
     * ログインユーザーのタグレビューを作成または更新
     *
     * @param Tag $tag
     * @param TagReviewRequest $submit_tag_review
     * @return void
     */
    public function createOrUpdateMyTagReview(Tag $tag, TagReviewRequest $submit_tag_review)
    {
        foreach ($submit_tag_review->modify_type as $key => $modify_type) {
            if (is_null($submit_tag_review->anime_id[$key]) || is_null($submit_tag_review->score[$key])) {
                continue;
            }
            if ($modify_type == 'no_change') {
                continue;
            }
            if ($modify_type == 'change') {
                $my_tag_review = $this->tagReviewRepository->getById($submit_tag_review->tag_review_id[$key]);
                $this->tagReviewRepository->updateTagReviewByScoreAndComment(
                    $my_tag_review,
                    $submit_tag_review->score[$key],
                    $submit_tag_review->comment[$key],
                );
            }
            if ($modify_type == 'delete') {
                $this->tagReviewRepository->getById($submit_tag_review->tag_review_id[$key]);
                $this->tagReviewRepository->deleteById($submit_tag_review->tag_review_id[$key]);
            }
            if ($modify_type == 'add') {
                $anime = $this->animeRepository->getById($submit_tag_review->anime_id[$key]);
                if ($this->isContainMyTagReviews($anime, $tag)) {
                    continue;
                }
                $this->tagReviewRepository->createByAnimeTagReviewRequest([
                    'anime_id' => $anime->id,
                    'user_id' => Auth::user()->id,
                    'tag_id' => $tag->id,
                    'score' => $submit_tag_review->score[$key],
                    'comment' => $submit_tag_review->comment[$key],
                ]);
            }
        }
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
        return $this->tagRepository->isContainMyTagReviews($anime, $tag);
    }
}
