@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの声優別視聴数 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
<article class="watch_review_cast_list">
    <h1>{{ $user->name }}さんの声優別視聴数</h1>
            <section class="watch_review_cast_list">
                <h2>声優別視聴数</h2>
                <div class="table-responsive">
                    <table class="watch_review_cast_list_table">
                        <tr>
                            <th>声優名</th>
                            <th>視聴数</th>
                            <th>得点平均</th>
                            <th>アニメ</th>
                        </tr>
                        @foreach ($cast_list as $cast)
                            <tr>
                                <td><a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a>
                                </td>
                                <td>{{ $cast->act_animes_count }}</td>
                                <td>{{ $cast->actAnimes->average('userReview.score') }}</td>
                                <td>
                                    <ul class="list-inline d-inline">
                                        @foreach ($cast->actAnimes->sortByDesc('userReview.score') as $anime)
                                            <li class="d-inline">
                                                <span style="font-size: 50%;">{{ $anime->userReview->score }}</span>
                                                <a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </section>
        </article>
    </div>
@endsection
