<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TagReviewRequest;
use App\Http\Requests\TagRequest;
use App\Services\TagService;
use App\Services\TagReviewService;
use App\Services\AnimeService;

class TagController extends Controller
{
    private TagService $tagService;
    private TagReviewService $tagReviewService;
    private AnimeService $animeService;

    public function __construct(
        TagService $tagService,
        TagReviewService $tagReviewService,
        AnimeService $animeService,
    ) {
        $this->tagService = $tagService;
        $this->tagReviewService = $tagReviewService;
        $this->animeService = $animeService;
    }

    /**
     * タグの情報を表示
     *
     * @param int $tag_id
     * @return \Illuminate\View\View
     */
    public function show($tag_id)
    {
        $tag = $this->tagService->getTagById($tag_id);
        $animes = $this->animeService->getAnimesByTagWithCompaniesTagReviews($tag);
        return view('tag', [
            'tag' => $tag,
            'animes' => $animes,
        ]);
    }
    /**
     * タグリストを表示
     *
     * @return \Illuminate\View\View
     */
    public function showList()
    {
        $tag_all = $this->tagService->getTagAllOldestTagGroupId();
        return view('tag_list', [
            'tag_all' => $tag_all,
        ]);
    }

    /**
     * タグをアニメに一括入力するページを表示
     *
     * @param int $tag_id
     * @return \Illuminate\View\View
     */
    public function showTagReview($tag_id)
    {
        $tag = $this->tagService->getTagWithMyTagReviewWithAnime($tag_id);
        return view('tag_review', [
            'tag' => $tag,
        ]);
    }

    /**
     * タグのアニメへの一括入力を処理し，タグページにリダイレクト
     *
     * @param int $tag_id
     * @param TagReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postTagReview($tag_id, TagReviewRequest $request)
    {
        $tag = $this->tagService->getTagById($tag_id);
        $this->tagReviewService->createOrUpdateMyTagReview($tag, $request);
        return redirect()->route('tag.show', [
            'tag_id' => $tag_id,
        ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * タグ情報変更ページを表示
     *
     * @param int $tag_id
     * @return \Illuminate\View\View
     */
    public function showModifyTagRequest($tag_id)
    {
        $tag = $this->tagService->getTagById($tag_id);
        return view('modify_tag_request', [
            'tag' => $tag,
        ]);
    }

    /**
     * タグ情報を変更し，元の画面にリダイレクト
     *
     * @param int $tag_id
     * @param TagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyTagRequest($tag_id, TagRequest $request)
    {
        $this->tagService->updateTagByRequest($tag_id, $request);
        return redirect()->route('modify_tag_request.show', [
            'tag_id' => $tag_id,
        ])->with('flash_message', '変更が完了しました。');
    }

    /**
     * タグIDを名前によって取得し、REST API形式で出力
     *
     * @param string $tag_name
     * @return int | string
     */
    public function getTagIdByNameForApi($tag_name)
    {
        return $this->tagService->getTagByNameAllowNull($tag_name)->id ?? '登録なし';
    }

    /**
     * タグ名をタグIDによって取得し、REST API形式で出力
     *
     * @param int $tag_id
     * @return string
     */
    public function getTagNameByIdForApi($tag_id)
    {
        return $this->tagService->getTagByIdAllowNull($tag_id)->name ?? '登録なし';
    }
}
