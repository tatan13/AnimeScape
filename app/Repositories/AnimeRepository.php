<?php

namespace App\Repositories;

use App\Models\Anime;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;

class AnimeRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return Anime::class;
    }

    /**
     *
     */
    public function getNowCoorAnimeList()
    {
        return Anime::whereYEAR(2022)->whereCoor(Anime::WINTER)->latest(Anime::TYPE_MEDIAN)->get();
    }

    /**
     *
     */
    public function getAnimeListForAllPeriods(Request $request)
    {
        return Anime::whereCount($request->count)->latest($request->category)->get();
    }

    /**
     *
     */
    public function getAnimeListForEachYear(Request $request)
    {
        return  Anime::whereYear($request->year)->whereCoor($request->count)
        ->latest($request->category)->get();
    }

    /**
     *
     */
    public function getAnimeListForEachCoor(Request $request)
    {
        return  Anime::whereCoor($request->coor)->whereYear($request->year)->whereCount($request->count)
        ->latest($request->category)->get();
    }

    /**
     *
     */
    public function getActCasts(Anime $anime)
    {
        return $anime->actCasts;
    }

    /**
     *
     */
    public function getUserReviewsOfAnime(Anime $anime)
    {
        return $anime->userReviews;
    }

    public function getLatestUserReviews(Anime $anime)
    {
        return $anime->userReviews()->with('user')->latest()->get();
    }

    /**
     *
     */
    public function getModifyOccupationListOfAnime(Anime $anime)
    {
        return $anime->modifyOccupations;
    }

    /**
     *
     */
    public function deleteModifyOccupationsOfAnime(Anime $anime)
    {
        $anime->modifyOccupations()->delete();
    }

    /**
     *
     */
    public function deleteOccupations(Anime $anime)
    {
        $anime->occupations()->delete();
    }

    /**
     * ログインユーザーのアニメレビューを取得
     * @param Anime $anime
     * @return UserReview
     */
    public function getMyReview(Anime $anime)
    {
        return $anime->userReviews()->where('user_id', Auth::id())->first();
    }

    public function createMyReview(Anime $anime, ReviewRequest $submit_score)
    {
        return $anime->reviewUsers()->attach(Auth::user()->id, $submit_score->validated());
    }

    public function updateMyReview(Anime $anime, ReviewRequest $submit_score)
    {
        return $anime->reviewUsers()->updateExistingPivot(Auth::user()->id, $submit_score->validated());
    }

    public function update(Anime $anime)
    {
        $anime->save();
    }
}
