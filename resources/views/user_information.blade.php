@extends('layout')

@section('title')
    <title>{{ $user_information->name }}さんの情報 AnimeScape -アニメ批評空間-</title>
    <link rel="canonical" href="https://www.animescape.link/{{ $user_information->id }}">
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection


@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@if (env('APP_ENV') == 'production')
    @section('main_adsense_smartphone')
        @include('layout.horizontal_adsense_smartphone')
    @endsection
@endif

@section('main')
    <div id="app">
        <article class="user_information">
            <h1>{{ $user_information->name }}さんの情報</h1>
            <div class="title">{{ $user_information->name }}</div>
            @auth
                @if (Auth::id() == $user_information->id)
                    <a href="{{ route('user_config.show') }}">個人情報設定</a>
                @else
                    <like-user-component :props-id="{{ json_encode($user_information->id) }}"
                        :default-liked-user-count="{{ json_encode($user_information->userLikedUsers->count()) }}"
                        :default-is-like-user="{{ json_encode(Auth::user()->isLikeUser($user_information->id)) }}">
                    </like-user-component>
                @endif
            @endauth
            @if (!is_null($user_information->one_comment))
                <p style="background-color: #FDFDAA;">{!! nl2br(e($user_information->one_comment)) !!}</p>
            @endif
            @if (!is_null($user_information->twitter))
                <p>Twitter : <a href="https://twitter.com/{{ $user_information->twitter }}" target="_blank"
                        rel="noopener noreferrer">{{ $user_information->twitter }}</a></p>
            @endif
            <section class="user_statistics_information">
                <div class="row">
                    <div class="col-md-6">
                        <h2>統計情報({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
                        </h2>
                        <div class="table-responsive">
                            <table class="user_statistics_information">
                                <tbody>
                                    <tr>
                                        <th>得点入力数</th>
                                        <td><a
                                                href="{{ route('user_score_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->score_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>得点の平均</th>
                                        <td>{{ $user_information->score_average }}</td>
                                    </tr>
                                    <tr>
                                        <th>得点の中央値</th>
                                        <td>{{ $user_information->score_median }}</td>
                                    </tr>
                                    <tr>
                                        <th>感想入力数</th>
                                        <td><a
                                                href="{{ route('user_comment_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->comments_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴予定数</th>
                                        <td><a
                                                href="{{ route('user_will_watch_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->will_watches_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_watch_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->watches_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴中数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_now_watch_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->now_watches_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴リタイア数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_give_up_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->give_ups_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴完了前得点入力数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_before_score_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->before_score_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴完了前一言感想入力数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_before_comment_anime_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">{{ $user_information->before_comments_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>お気に入りユーザー数</th>
                                        <td><a
                                                href="{{ route('user_like_user_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->userLikeUsers->count() }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>被お気に入りユーザー数</th>
                                        <td><a
                                                href="{{ route('user_liked_user_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->userLikedUsers->count() }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>お気に入り声優数</th>
                                        <td><a
                                                href="{{ route('user_like_cast_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->likeCasts->count() }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>お気に入りクリエイター数</th>
                                        <td><a
                                                href="{{ route('user_like_creater_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->likeCreaters->count() }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2>得点分布</h2>
                        <div class="table-responsive">
                            <table class="score_distribution_table">
                                <tbody>
                                    <tr>
                                        <th>状況</th>
                                        <th>度数</th>
                                    </tr>
                                    <tr>
                                        <td>100</td>
                                        <td>{{ $user_information['score_100_anime_reviews']->count() }}</td>
                                    </tr>
                                    @for ($i = 90; $i >= 0; $i = $i - 10)
                                        <tr>
                                            <td>{{ $i }}～{{ $i + 9 }}</td>
                                            <td>{{ $user_information->{'score_' . $i . '_anime_reviews'}->count() + $user_information->{'score_' . $i + 5 . '_anime_reviews'}->count() }}
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <section class="anime_score_list">
                <h2>得点とアニメの対応表({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
                </h2>
                <form action="{{ route('user.show', ['user_id' => $user_information->id]) }}"
                    class="search_parameters_form" name="coor_score_animelist" method="get">
                    @csrf
                    <select name="year" class="year">
                        <option value="">-</option>
                        @include('layout/select_year')
                    </select>
                    年
                    <select name="coor" class="coor">
                        <option value="">-</option>
                        <option value="1" {{ is_null($coor ?? null) ? '' : ($coor == 1 ? 'selected' : '') }}>冬
                        </option>
                        <option value="2" {{ is_null($coor ?? null) ? '' : ($coor == 2 ? 'selected' : '') }}>春
                        </option>
                        <option value="3" {{ is_null($coor ?? null) ? '' : ($coor == 3 ? 'selected' : '') }}>夏
                        </option>
                        <option value="4" {{ is_null($coor ?? null) ? '' : ($coor == 4 ? 'selected' : '') }}>秋
                        </option>
                    </select>
                    <input type="submit" value="絞り込み"> <a
                        href="{{ route('user.show', ['user_id' => $user_information->id]) }}">絞り込み解除</a>
                </form>
                <div class="table-responsive">
                    <table class="anime_score_list_table">
                        <thead>
                            <tr>
                                <th>得点</th>
                                <th>アニメ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 100; $i >= 0; $i = $i - 5)
                                <tr>
                                    <th>{{ $i }}</th>
                                    <td>
                                        <ul class="list-inline d-inline">
                                            @foreach ($user_information->{'score_' . $i . '_anime_reviews'} as ${'score_' . $i . '_anime_review'})
                                                <li class="d-inline">
                                                    <span
                                                        style="font-size: 50%;">{{ ${'score_' . $i . '_anime_review'}->score }}</span>
                                                    <a
                                                        href="{{ route('anime.show', ['anime_id' => ${'score_' . $i . '_anime_review'}->anime->id]) }}">{{ ${'score_' . $i . '_anime_review'}->anime->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="watch_review_company_list">
                <h2>制作会社別視聴数({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
                </h2>
                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button"
                    data-text="{{ $user_information->name }}さんの{{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }}の制作会社別視聴数 @foreach ($company_list->take(5) as $company){{ $company->name }}({{ $company->animes_count }}本) @endforeach..."
                    data-url="{{ route('user.show', ['user_id' => $user_information->id]) }}" data-hashtags="AnimeScape"
                    data-related="tatan_tech" data-show-count="false">Tweet</a>
                <form action="{{ route('user.show', ['user_id' => $user_information->id]) }}"
                    class="search_parameters_form" name="coor_score_animelist" method="get">
                    @csrf
                    <select name="year" class="year">
                        <option value="">-</option>
                        @include('layout/select_year')
                    </select>
                    年
                    <select name="coor" class="coor">
                        <option value="">-</option>
                        <option value="1" {{ is_null($coor ?? null) ? '' : ($coor == 1 ? 'selected' : '') }}>冬
                        </option>
                        <option value="2" {{ is_null($coor ?? null) ? '' : ($coor == 2 ? 'selected' : '') }}>春
                        </option>
                        <option value="3" {{ is_null($coor ?? null) ? '' : ($coor == 3 ? 'selected' : '') }}>夏
                        </option>
                        <option value="4" {{ is_null($coor ?? null) ? '' : ($coor == 4 ? 'selected' : '') }}>秋
                        </option>
                    </select>
                    <input type="submit" value="絞り込み"> <a
                        href="{{ route('user.show', ['user_id' => $user_information->id]) }}">絞り込み解除</a>
                </form>
                <div class="table-responsive">
                    <table class="watch_review_company_list_table">
                        <tr>
                            <th>会社名</th>
                            <th>視聴数</th>
                            <th>得点平均</th>
                            <th>アニメ</th>
                        </tr>
                        @foreach ($company_list as $company)
                            <tr>
                                <td><a
                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                </td>
                                <td>{{ $company->animes_count }}</td>
                                <td>{{ (int) $company->animes->average('userReview.score') }}</td>
                                <td>
                                    <ul class="list-inline d-inline">
                                        @foreach ($company->animes->sortByDesc('userReview.score') as $anime)
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
                →<a
                    href="{{ route('user_watch_review_company_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">すべて見る</a>
            </section>
            <section class="watch_review_cast_list">
                <h2>声優別視聴数({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
                </h2>
                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button"
                    data-text="{{ $user_information->name }}さんの{{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }}の声優別視聴数 @foreach ($cast_list->take(5) as $cast){{ $cast->name }}({{ $cast->act_animes_count }}本) @endforeach..."
                    data-url="{{ route('user.show', ['user_id' => $user_information->id]) }}" data-hashtags="AnimeScape"
                    data-related="tatan_tech" data-show-count="false">Tweet</a>
                <form action="{{ route('user.show', ['user_id' => $user_information->id]) }}"
                    class="search_parameters_form" name="coor_score_animelist" method="get">
                    @csrf
                    <select name="year" class="year">
                        <option value="">-</option>
                        @include('layout/select_year')
                    </select>
                    年
                    <select name="coor" class="coor">
                        <option value="">-</option>
                        <option value="1" {{ is_null($coor ?? null) ? '' : ($coor == 1 ? 'selected' : '') }}>冬
                        </option>
                        <option value="2" {{ is_null($coor ?? null) ? '' : ($coor == 2 ? 'selected' : '') }}>春
                        </option>
                        <option value="3" {{ is_null($coor ?? null) ? '' : ($coor == 3 ? 'selected' : '') }}>夏
                        </option>
                        <option value="4" {{ is_null($coor ?? null) ? '' : ($coor == 4 ? 'selected' : '') }}>秋
                        </option>
                    </select>
                    <input type="submit" value="絞り込み"> <a
                        href="{{ route('user.show', ['user_id' => $user_information->id]) }}">絞り込み解除</a>
                </form>
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
                                <td>{{ (int) $cast->actAnimes->average('userReview.score') }}</td>
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
                →<a
                    href="{{ route('user_watch_review_cast_list.show', ['user_id' => $user_information->id, 'year' => $year, 'coor' => $coor]) }}">すべて見る</a>
            </section>
        </article>
    </div>
@endsection
@section('vue.js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
