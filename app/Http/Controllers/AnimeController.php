<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Requests\TagReviewRequest;
use App\Http\Requests\ReviewsRequest;
use App\Services\AnimeService;
use App\Services\TagService;
use App\Services\UserReviewService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    private AnimeService $animeService;
    private TagService $tagService;
    private UserReviewService $userReviewService;

    public function __construct(
        AnimeService $animeService,
        TagService $tagService,
        UserReviewService $userReviewService,
    ) {
        $this->animeService = $animeService;
        $this->tagService = $tagService;
        $this->userReviewService = $userReviewService;
    }

    /**
     * アニメの情報を表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function show($anime_id)
    {
        $anime = $this->animeService
        ->getAnimeWithCompaniesMyReviewOccupationsAnimeCreatersUserReviewsUser($anime_id);
        $tags = $this->tagService->getTagsByAnimeWithTagReviewsAndUser($anime);
        return view('anime', [
            'anime' => $anime,
            'tags' => $tags,
        ]);
    }

    /**
     * アニメの得点画面を表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showAnimeReview($anime_id)
    {
        $anime = $this->animeService->getAnimeWithMyReview($anime_id);
        return view('anime_review', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの入力された得点を処理し，アニメページにリダイレクト
     *
     * @param int $anime_id
     * @param ReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAnimeReview($anime_id, ReviewRequest $request)
    {
        $anime = $this->animeService->getAnime($anime_id);
        $this->userReviewService->createOrUpdateMyReview($anime, $request);
        return redirect()->route('anime.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * アニメのタグ入力ページを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showTagReview($anime_id)
    {
        $anime = $this->animeService->getAnimeWithMyTagReview($anime_id);
        return view('tag_review', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの入力されたタグを処理し，アニメページにリダイレクト
     *
     * @param int $anime_id
     * @param TagReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postTagReview($anime_id, TagReviewRequest $request)
    {
        $anime = $this->animeService->getAnime($anime_id);
        $this->userReviewService->createOrUpdateMyTagReview($anime, $request);
        return redirect()->route('anime.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * アニメの一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showAnimeReviewList(Request $request)
    {
        $anime_list = $this->animeService->getAnimeListWithCompaniesAndWithMyReviewsFor($request);
        return view('anime_review_list', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * アニメの一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAnimeReviewList(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('anime_review_list.show', [
            'year' => $request->year,
            'coor' => $request->coor,
            ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * アニメの得点リストを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showAnimeScoreList($anime_id)
    {
        $anime = $this->animeService->getAnimeWithUserReviewsWithUserNotNullScoreLatest($anime_id);
        return view('anime_score_list', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの視聴完了前得点リストを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showAnimeBeforeScoreList($anime_id)
    {
        $anime = $this->animeService->getAnimeWithUserReviewsWithUserNotNullBeforeScoreLatest($anime_id);
        return view('anime_before_score_list', [
            'anime' => $anime,
        ]);
    }

    /**
     * 全てのアニメを取得し、json形式で出力
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllAnimeList()
    {
        $anime_list_by_json = $this->animeService->getAllAnimeListByJson();
        return response($anime_list_by_json, 200);
    }
}
