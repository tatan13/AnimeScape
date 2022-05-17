@extends('layout')

@section('title')
    <title>AnimeScape</title>
@endsection

@section('main')
    <article class="index">
        <h2>トップページ</h2>
        <section class="information">
            <h3>お知らせ</h3>
            2000年～2013年のアニメ作品を追加しました。<br>
            また、アニメ作品の追加、削除申請機能を実装しましたので、データの抜け、重複などがありましたら申請していだだけると助かります。
        </section>
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
                            @auth
                                <th>つけた得点</th>
                            @endauth
                        </tr>
                        @foreach ($recommend_anime_list as $anime)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                </td>
                                <td>{{ $anime->company }}</td>
                                <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                <td>{{ $anime->median }}</td>
                                <td>{{ $anime->count }}</td>
                                @auth
                                    <td>{{ $anime->userReview->score ?? '' }}</td>
                                @endauth
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
                        @auth
                            <th>つけた得点</th>
                        @endauth
                    </tr>
                    @foreach ($animes as $anime)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a
                                    href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                            </td>
                            <td>{{ $anime->company }}</td>
                            <td>{{ $anime->median }}</td>
                            <td>{{ $anime->count }}</td>
                            @auth
                                <td>{{ $anime->userReview->score ?? '' }}</td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </article>
@endsection
