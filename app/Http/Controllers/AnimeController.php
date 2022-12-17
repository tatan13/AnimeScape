<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Requests\AnimeTagReviewRequest;
use App\Http\Requests\ReviewsRequest;
use App\Services\AnimeService;
use App\Services\TagService;
use App\Services\TagReviewService;
use App\Services\UserReviewService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    private AnimeService $animeService;
    private TagService $tagService;
    private TagReviewService $tagReviewService;
    private UserReviewService $userReviewService;
    private UserService $userService;

    public function __construct(
        AnimeService $animeService,
        TagService $tagService,
        TagReviewService $tagReviewService,
        UserReviewService $userReviewService,
        UserService $userService,
    ) {
        $this->animeService = $animeService;
        $this->tagService = $tagService;
        $this->tagReviewService = $tagReviewService;
        $this->userReviewService = $userReviewService;
        $this->userService = $userService;
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
        $like_users = $this->userService->getWatchAnimeLikeUsersOfLoginUser($anime);
        return view('anime', [
            'anime' => $anime,
            'tags' => $tags,
            'like_users' => $like_users,
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
        $anime = $this->animeService->getAnimeById($anime_id);
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
    public function showAnimeTagReview($anime_id)
    {
        $anime = $this->animeService->getAnimeWithMyTagReview($anime_id);
        return view('anime_tag_review', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの入力されたタグを処理し，アニメページにリダイレクト
     *
     * @param int $anime_id
     * @param AnimeTagReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAnimeTagReview($anime_id, AnimeTagReviewRequest $request)
    {
        $anime = $this->animeService->getAnimeById($anime_id);
        $this->tagReviewService->createOrUpdateMyAnimeTagReview($anime, $request);
        return redirect()->route('anime.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * アニメのデータ一括入力のインデックスページを表示
     *
     * @return \Illuminate\View\View
     */
    public function showAnimeBulkReviewIndex()
    {
        return view('anime_bulk_review_index');
    }

    /**
     * クール毎のアニメの一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCoorAnimeBulkReview(Request $request)
    {
        $anime_list = $this->animeService->getCoorAnimeListWithMyReviewsFor($request);
        return view('coor_anime_bulk_review', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * クール毎のアニメの一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCoorAnimeBulkReview(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('coor_anime_bulk_review.show', [
            'year' => $request->year,
            'coor' => $request->coor,
            ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * ログインユーザーの視聴中のアニメの一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showNowWatchAnimeBulkReview(Request $request)
    {
        $anime_list = $this->animeService->getNowWatchAnimeListWithMyReviewsFor($request);
        return view('now_watch_anime_bulk_review', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ログインユーザーの視聴中のアニメの一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNowWatchAnimeBulkReview(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('now_watch_anime_bulk_review.show', [
            'year' => $request->year,
            'coor' => $request->coor,
            ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * ログインユーザーの得点入力済みのアニメの一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showScoreAnimeBulkReview(Request $request)
    {
        $anime_list = $this->animeService->getScoreAnimeListWithMyReviewsFor($request);
        return view('score_anime_bulk_review', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ログインユーザーの得点入力済みのアニメの一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postScoreAnimeBulkReview(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('score_anime_bulk_review.show', [
            'year' => $request->year,
            'coor' => $request->coor,
            ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * クール毎のアニメの視聴完了前一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCoorAnimeBulkBeforeReview(Request $request)
    {
        $anime_list = $this->animeService->getCoorAnimeListWithMyReviewsFor($request);
        return view('coor_anime_bulk_before_review', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * クール毎のアニメの視聴完了前一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCoorAnimeBulkBeforeReview(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('coor_anime_bulk_before_review.show', [
            'year' => $request->year,
            'coor' => $request->coor,
            ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * ログインユーザーの視聴中のアニメの視聴完了前一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showNowWatchAnimeBulkBeforeReview(Request $request)
    {
        $anime_list = $this->animeService->getNowWatchAnimeListWithMyReviewsFor($request);
        return view('now_watch_anime_bulk_before_review', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ログインユーザーの視聴中のアニメの視聴完了前一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNowWatchAnimeBulkBeforeReview(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('now_watch_anime_bulk_before_review.show', [
            'year' => $request->year,
            'coor' => $request->coor,
            ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * ログインユーザーの視聴完了前得点入力済みのアニメの一括得点入力ページを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showBeforeScoreAnimeBulkBeforeReview(Request $request)
    {
        $anime_list = $this->animeService->getBeforeScoreAnimeListWithMyReviewsFor($request);
        return view('before_score_anime_bulk_before_review', [
            'anime_list' => $anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ログインユーザーの視聴完了前得点入力済みのアニメの一括入力された得点を処理
     *
     * @param ReviewsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBeforeScoreAnimeBulkBeforeReview(ReviewsRequest $request)
    {
        $this->userReviewService->createOrUpdateMyMultipleReview($request);
        return redirect()->route('before_score_anime_bulk_before_review.show', [
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

    /**
     * アニメIDをアニメタイトルによって取得し、REST API形式で出力
     *
     * @param string $anime_title
     * @return int | string
     */
    public function getAnimeIdByTitleForApi($anime_title)
    {
        return $this->animeService->getAnimeByTitleAllowNull($anime_title)->id ?? '登録なし';
    }

    /**
     * アニメタイトルをアニメIDによって取得し、REST API形式で出力
     *
     * @param int $anime_id
     * @return string
     */
    public function getAnimeTitleByIdForApi($anime_id)
    {
        return $this->animeService->getAnimeByIdAllowNull($anime_id)->title ?? '登録なし';
    }

    /**
     * アニメリストを表示
     *
     * @return \Illuminate\View\View
     */
    public function showList()
    {
        $anime_all = $this->animeService->getAnimeAll();
        return view('anime_list', [
            'anime_all' => $anime_all,
        ]);
    }
}
