@extends('layout')

@section('title')
    <title>AnimeScape</title>
@endsection

@section('main')
    <article class="index">
        <h2>トップページ</h2>
        <section class="recommend_anime_list">
            @auth
                <h3>あなたへのおすすめアニメ</h3>
                <table class="recommend_anime_list_table">
                    <tbody>
                        <tr>
                            <th>順位</th>
                            <th>アニメ名</th>
                            <th>会社名</th>
                            <th>放送クール</th>
                            <th>中央値</th>
                            <th>データ数</th>
                        </tr>
                        @foreach ($recommend_anime_list as $anime)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                                </td>
                                <td>{{ $anime->company }}</td>
                                <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                <td>{{ $anime->median }}</td>
                                <td>{{ $anime->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endauth
        </section>
        <section class="anime_ranking">
            <h3>2022年冬クールアニメランキング</h3>
            <table class="anime_ranking_table">
                <tbody>
                    <tr>
                        <th>順位</th>
                        <th>アニメ名</th>
                        <th>会社名</th>
                        <th>中央値</th>
                        <th>データ数</th>
                    </tr>
                    @foreach ($animes as $anime)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                            </td>
                            <td>{{ $anime->company }}</td>
                            <td>{{ $anime->median }}</td>
                            <td>{{ $anime->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </article>
@endsection
