<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\UserReview;
use App\Http\Requests\SubmitScore;
use App\Services\AnimeService;
use App\Services\ExceptionService;
use App\Repositories\AnimeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimeController extends Controller
{
    private $animeService;
    private $exceptionService;

    public function __construct(AnimeService $animeService, ExceptionService $exceptionService)
    {
        $this->animeService = $animeService;
        $this->exceptionService = $exceptionService;
    }

    /**
     * アニメの情報を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $anime = Anime::find($id);//$this->animeService->getAnime($id);
        $this->exceptionService->render404IfNotExist($anime);
        $user_reviews = $anime->userReviews()->with('user')->get()->sortByDesc('created_at');//$this->animeService->getAnimeById($id);
        $anime_casts = $anime->actCasts;
        $my_review = $this->getMyReview($anime);
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
    public function score($id)
    {
        $anime = Anime::find($id);
        $this->exceptionService->render404IfNotExist($anime);
        $my_review = $this->getMyReview($anime);
        return view('score_anime', [
            'anime' => $anime,
            'my_review' => $my_review,
        ]);
    }

    /**
     * アニメの入力された得点を処理し，得点画面にリダイレクト
     *
     * @param int $id
     * @param SubmitScore $request
     * @param AnimeRepository $anime_repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postScore($id, SubmitScore $request, AnimeRepository $anime_repository)
    {
        $anime = Anime::find($id);

        $this->exceptionService->render404IfNotExist($anime);

        $my_review = $this->getMyReview($anime);


        //要修正
        //$anime_repository->create($request);
        
        // 入力した得点をuser_reviewsテーブルに格納
        $my_review->anime_id = $id;
        $my_review->score = $request->score;
        $my_review->one_word_comment = $request->one_comment;
        if ($request->spoiler) {
            $my_review->spoiler = TRUE;
        } else {
            $my_review->spoiler = FALSE;
        }

        if ($request->will_watch) {
            $my_review->will_watch = TRUE;
        } else {
            $my_review->will_watch = FALSE;
        }

        if ($request->watch) {
            $my_review->watch = TRUE;
            $my_review->will_watch = FALSE;
        } else {
            $my_review->watch = FALSE;
        }
        

        DB::transaction(function () use ($my_review, $anime) {
            Auth::user()->userReviews()->save($my_review);
            $this->updateScoreStatistics($anime);
        });
        return redirect()->route('anime', [
            'id' => $id,
        ])->with('flash_message', '入力が完了しました。');
    }

    /**
     * アニメの得点統計情報を更新
     *
     * @param Anime $anime
     * @return void
     */
    public function updateScoreStatistics($anime)
    {
        $user_reviews = $anime->userReviews;
        $anime->median = $user_reviews->median('score');
        $anime->average = $user_reviews->avg('score');
        $anime->max = $user_reviews->max('score');
        $anime->min = $user_reviews->min('score');
        $anime->count = $user_reviews->count();
        $anime->save();
    }

    /**
     * ログインユーザーのアニメレビューを取得
     * @param Anime $anime
     * @return UserReview
     */
    public function getMyReview($anime)
    {
        if (Auth::check()){
            $my_review = $anime->userReviews()->where('user_id', Auth::id())->first();
            if (empty($my_review)) {
                $my_review = new UserReview();
            }
            return $my_review;
        } else {
            $my_review = null;
        }
    }
}