@extends('layout')

@section('title')
    <meta name="description" content="アニメの情報やアニメに付けたユーザーの得点や感想を集めた統計サイトです。視聴管理、アニメ探し等様々な用途でご利用ください。">
    <link rel="canonical" href="https://www.animescape.link/">
    <title>AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection


@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <article class="index">
        <section class="information">
            <h1>AnimeScape -アニメ批評空間-</h1>
            <h2>アニメの批評・評価、視聴管理ができる統計サイトAnimeScape -アニメ批評空間-へようこそ</h2>
            このサイトはアニメの情報やアニメに付けたユーザーの得点や感想を集めた統計サイトです。アニメの批評・評価、視聴管理、アニメ探し等様々な用途でご利用ください。詳しくは<a
                href="{{ route('site_information.show') }}">このサイトについて</a>を一読ください。Twitterアカウント(<a
                href="https://twitter.com/tatan_tech" target="_blank"
                rel="noopener noreferrer">@tatan_tech</a>)も運用しています。更新情報等を呟いていますので是非フォローをお願いします。
            <h2>お知らせ</h2>
            <ul class="list-inline">
                <li>視聴完了前得点とアニメの対応表を追加しました。ユーザーページのリンクから飛べる視聴完了前得点数ページに表示しています。</li>
                <li>商品情報を追加しました。</li>
            </ul>
        </section>
        <section class="anime_ranking">
            <h2>{{ \App\Models\Anime::NOW_YEAR }}年{{ App\Models\Anime::getCoorLabel(\App\Models\Anime::NOW_COOR) }}クールアニメ視聴完了前ランキング
            </h2>
            <div class="table-responsive">
                <table class="anime_ranking_table">
                    <tbody>
                        <tr>
                            <th>順位</th>
                            <th>アニメ名</th>
                            <th>制作会社</th>
                            <th>放送媒体</th>
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
            </div>
        </section>
        <section class="new_before_comment">
            <h2>新着視聴完了前感想　<a href="{{ route('new_before_comment_list.show') }}">もっと見る</a></h2>
            @foreach ($user_reviews_latest_before_comment as $user_review)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
                @if (!is_null($user_review->before_score))
                    <strong>{{ $user_review->before_score }}点</strong>
                @endif
                <a
                    href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
                <br>
                {{ $user_review->before_comment }}
                @if (!is_null($user_review->before_long_comment))
                    <a href="{{ route('user_anime_before_comment.show', ['user_review_id' => $user_review->id]) }}">→長文感想({{ mb_strlen($user_review->before_long_comment) }}文字)
                        @if ($user_review->before_comment_spoiler == true)
                            (ネタバレ注意)
                        @endif
                    </a>
                @endif
                <br>
                {{ $user_review->before_comment_timestamp }} <a
                    href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
                </div>
            @endforeach
        </section>
        @if (env('APP_ENV') == 'production')
            @include('layout.horizontal_adsense')
        @endif
        <section class="new_comment">
            <h2>新着感想　<a href="{{ route('new_comment_list.show') }}">もっと見る</a></h2>
            @foreach ($user_reviews_latest_comment as $user_review)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
                @if (!is_null($user_review->score))
                    <strong>{{ $user_review->score }}点</strong>
                @endif
                <a
                    href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
                <br>
                {{ $user_review->one_word_comment }}
                @if (!is_null($user_review->long_word_comment))
                    <a href="{{ route('user_anime_comment.show', ['user_review_id' => $user_review->id]) }}">→長文感想({{ mb_strlen($user_review->long_word_comment) }}文字)
                        @if ($user_review->spoiler == true)
                            (ネタバレ注意)
                        @endif
                    </a>
                @endif
                <br>
                {{ $user_review->comment_timestamp }} <a
                    href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
                </div>
            @endforeach
        </section>
    </article>
@endsection
