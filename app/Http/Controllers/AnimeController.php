<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Services\AnimeService;
use App\Services\UserReviewService;
use App\Services\CastService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    private $animeService;
    private $userReviewService;
    private $castService;

    public function __construct(
        AnimeService $animeService,
        UserReviewService $userReviewService,
        CastService $castService,
    ) {
        $this->animeService = $animeService;
        $this->userReviewService = $userReviewService;
        $this->castService = $castService;
    }

    /**
     * アニメの情報を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $anime = $this->animeService->getAnime($id);
        $user_reviews = $this->userReviewService->getLatestUserReviewsOfAnimeWithUser($anime);
        $anime_casts = $this->castService->getActCasts($anime);
        $my_review = $this->userReviewService->getMyReview($anime);
        return view('anime', [
            'anime' => $anime,
            'user_reviews' => $user_reviews,
            'my_review' => $my_review,
            'anime_casts' => $anime_casts,
        ]);
    }

    /**
     * アニメの得点画面を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showAnimeReview($id)
    {
        $anime = $this->animeService->getAnime($id);
        $my_review = $this->userReviewService->getMyReview($anime);
        return view('anime_review', [
            'anime' => $anime,
            'my_review' => $my_review,
        ]);
    }

    /**
     * アニメの入力された得点を処理し，得点画面にリダイレクト
     *
     * @param int $id
     * @param ReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAnimeReview($id, ReviewRequest $request)
    {
        $anime = $this->animeService->getAnime($id);
        $this->userReviewService->createOrUpdateMyReview($anime, $request);
        return redirect()->route('anime.show', [
            'id' => $id,
        ])->with('flash_message', '入力が完了しました。');
    }
}
