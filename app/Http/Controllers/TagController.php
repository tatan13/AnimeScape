<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TagService;
use App\Services\AnimeService;

class TagController extends Controller
{
    private TagService $tagService;
    private AnimeService $animeService;

    public function __construct(
        TagService $tagService,
        AnimeService $animeService,
    ) {
        $this->tagService = $tagService;
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
     * タグを取得し、REST API形式で出力
     *
     * @param string $tag_name
     * @return int
     */
    public function getTagIdByName($tag_name)
    {
        $tag_name = $this->tagService->getTagIdForApiByName($tag_name);
        return $tag_name;
    }
}
