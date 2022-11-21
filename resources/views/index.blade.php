@extends('layout')

@section('title')
    <title>AnimeScape -アニメ批評空間-</title>
    <meta name="description" content="アニメの情報やアニメに付けたユーザーの得点や感想を集めた統計サイトです。視聴管理、アニメ探し等様々な用途でご利用ください。">
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
            <ul class="list-inline">
                <li>アニメのレビューに視聴完了前得点，視聴完了前一言感想を追加しました。今後はこの得点を用いて今期アニメの評価をしていきます。</li>
                <li>アニメの統計情報に標準偏差を追加しました。</li>
                <li>アニメに得点を付けたユーザーリストページを追加しました。アニメページの得点数リンクから飛べます。</li>
                <li>2022年秋アニメの追加が完了しました。</li>
                <li>現在はアニメ作品のジャンル、傾向等を表せるタグ機能の実装を進めています。</li>
            </ul>
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
                            <th>@sortablelink('stdev', '標準偏差')</th>
                            <th>@sortablelink('count', '得点数')</th>
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
                                <td>{{ $anime->stdev }}</td>
                                <td>{{ $anime->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endauth
        </section>
        <section class="anime_ranking">
            <h3>2022年{{ App\Models\Anime::getCoorLabel(\App\Models\Anime::NOW_COOR) }}クールアニメ視聴完了前ランキング</h3>
            <table class="anime_ranking_table">
                <tbody>
                    <tr>
                        <th>順位</th>
                        <th>アニメ名</th>
                        <th>制作会社</th>
                        <th>放送カテゴリー</th>
                        <th>@sortablelink('number_of_episode', '話数')</th>
                        <th>@sortablelink('before_median', '中央値')</th>
                        <th>@sortablelink('before_average', '平均値')</th>
                        <th>@sortablelink('before_stdev', '標準偏差')</th>
                        <th>@sortablelink('before_count', '得点数')</th>
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
                            <td>{{ $anime->media_category_label }}</td>
                            <td>{{ $anime->number_of_episode }}</td>
                            <td>{{ $anime->before_median }}</td>
                            <td>{{ $anime->before_average }}</td>
                            <td>{{ $anime->before_stdev }}</td>
                            <td>{{ $anime->before_count }}</td>
                            @auth
                                <td>{{ $anime->userReview->before_score ?? '' }}</td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </article>
@endsection
