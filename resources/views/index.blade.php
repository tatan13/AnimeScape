@extends('layout')

@section('title')
    <title>AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="index">
        <h2>トップページ</h2>
        <section class="information">
            <h3>お知らせ</h3>
            2022年夏アニメの追加が完了しました。<br>
            感想リストページを追加しました。<br>
            現在はアニメ作品のジャンル、傾向等を表せるタグ機能の実装を進めています。
        </section>
        <section class="recommend_anime_list">
            @auth
                <h3>あなたへのおすすめアニメ</h3>
                <table class="recommend_anime_list_table">
                    <tbody>
                        <tr>
                            <th>順位</th>
                            <th>アニメ名</th>
                            <th>制作会社</th>
                            <th>@sortablelink('unionYearCoor', '放送クール')</th>
                            <th>@sortablelink('number_of_episode', '話数')</th>
                            <th>@sortablelink('median', '中央値')</th>
                            <th>@sortablelink('average', '平均値')</th>
                            <th>@sortablelink('count', 'データ数')</th>
                        </tr>
                        @foreach ($recommend_anime_list as $anime)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                </td>
                                <td>
                                    @foreach ($anime->companies as $company)
                                        <a
                                            href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                    @endforeach
                                </td>
                                <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                <td>{{ $anime->number_of_episode }}</td>
                                <td>{{ $anime->median }}</td>
                                <td>{{ $anime->average }}</td>
                                <td>{{ $anime->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endauth
        </section>
        <section class="anime_ranking">
            <h3>2022年{{ App\Models\Anime::getCoorLabel(\App\Models\Anime::NOW_COOR) }}クールアニメランキング</h3>
            <table class="anime_ranking_table">
                <tbody>
                    <tr>
                        <th>順位</th>
                        <th>アニメ名</th>
                        <th>制作会社</th>
                        <th>@sortablelink('number_of_episode', '話数')</th>
                        <th>@sortablelink('median', '中央値')</th>
                        <th>@sortablelink('average', '平均値')</th>
                        <th>@sortablelink('count', 'データ数')</th>
                        @auth
                            <th>つけた得点</th>
                        @endauth
                    </tr>
                    @foreach ($animes as $anime)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                            </td>
                            <td>
                                @foreach ($anime->companies as $company)
                                    <a
                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                @endforeach
                            </td>
                            <td>{{ $anime->number_of_episode }}</td>
                            <td>{{ $anime->median }}</td>
                            <td>{{ $anime->average }}</td>
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
