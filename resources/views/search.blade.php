@extends('layout')

@section('title')
    <title>AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="search_result">
        @switch($category)
            @case('anime')
                <section class="anime_search_result">
                    <h2>検索結果 anime:{{ $search_word }}</h2>
                    @if (!$search_results->isEmpty())
                        <table class="anime_search_result_table">
                            <tbody>
                                <tr>
                                    <th>@sortablelink('title', 'アニメ名')</th>
                                    <th>制作会社</th>
                                    <th>@sortablelink('unionYearCoor', '放送クール')</th>
                                    <th>@sortablelink('median', '中央値')</th>
                                    <th>@sortablelink('average', '平均値')</th>
                                    <th>@sortablelink('count', 'データ数')</th>
                                    @auth
                                        <th>つけた得点</th>
                                    @endauth
                                </tr>
                                @foreach ($search_results as $anime)
                                    <tr>
                                        <td><a
                                                href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                        </td>
                                        <td>
                                            @foreach ($anime->companies as $company)
                                                <a
                                                    href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                            @endforeach
                                        </td>
                                        <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                        <td>{{ $anime->median }}</td>
                                        <td>{{ $anime->stdev }}</td>
                                        <td>{{ $anime->count }}</td>
                                        @auth
                                            <td>{{ $anime->userReview->score ?? '' }}</td>
                                        @endauth
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        該当するアニメがありませんでした。
                    @endif
                </section>
            @break

            @case('cast')
                <section class="cast_search_result">
                    <h2>検索結果 cast:{{ $search_word }}</h2>
                    @if (!$search_results->isEmpty())
                        @foreach ($search_results as $cast)
                            <h3><a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a></h3>
                            <strong>出演アニメ</strong>
                            <table class="cast_search_result_table">
                                <tbody>
                                    <tr>
                                        <th>アニメ名</th>
                                        <th>制作会社</th>
                                        <th>放送クール</th>
                                        <th>中央値</th>
                                        <th>データ数</th>
                                        @auth
                                            <th>つけた得点</th>
                                        @endauth
                                    </tr>
                                    @foreach ($cast->actAnimes as $act_anime)
                                        <tr>
                                            <td><a
                                                    href="{{ route('anime.show', ['anime_id' => $act_anime->id]) }}">{{ $act_anime->title }}</a>
                                            </td>
                                            <td>
                                                @foreach ($act_anime->companies as $company)
                                                    <a
                                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $act_anime->year }}年{{ $act_anime->coor_label }}クール
                                            </td>
                                            <td>{{ $act_anime->median }}</td>
                                            <td>{{ $act_anime->count }}</td>
                                            @auth
                                                <td>{{ $anime->userReview->score ?? '' }}</td>
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        該当する声優がいませんでした。
                    @endif
                </section>
            @break

            @case('creater')
                <section class="creater_search_result">
                    <h2>検索結果 creater:{{ $search_word }}</h2>
                    @if (!$search_results->isEmpty())
                        @foreach ($search_results as $creater)
                            <h3><a href="{{ route('creater.show', ['creater_id' => $creater->id]) }}">{{ $creater->name }}</a></h3>
                            <strong>関わったアニメ</strong>
                            <table class="creater_search_result_table">
                                <tbody>
                                    <tr>
                                        <th>アニメ名</th>
                                        <th>制作会社</th>
                                        <th>放送クール</th>
                                        <th>中央値</th>
                                        <th>データ数</th>
                                        @auth
                                            <th>つけた得点</th>
                                        @endauth
                                    </tr>
                                    @foreach ($creater->animes as $anie)
                                        <tr>
                                            <td><a
                                                    href="{{ route('anime.show', ['anime_id' => $anie->id]) }}">{{ $anie->title }}</a>
                                            </td>
                                            <td>
                                                @foreach ($anie->companies as $company)
                                                    <a
                                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $anie->year }}年{{ $anie->coor_label }}クール
                                            </td>
                                            <td>{{ $anie->median }}</td>
                                            <td>{{ $anie->count }}</td>
                                            @auth
                                                <td>{{ $anime->userReview->score ?? '' }}</td>
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        該当するクリエイターがいませんでした。
                    @endif
                </section>
            @break

            @case('company')
                <section class="company_search_result">
                    <h2>検索結果 company:{{ $search_word }}</h2>
                    @if (!$search_results->isEmpty())
                        @foreach ($search_results as $company)
                            <h3><a href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                            </h3>
                            <strong>制作アニメ</strong>
                            <table class="company_search_result_table">
                                <tbody>
                                    <tr>
                                        <th>アニメ名</th>
                                        <th>放送クール</th>
                                        <th>中央値</th>
                                        <th>データ数</th>
                                        @auth
                                            <th>つけた得点</th>
                                        @endauth
                                    </tr>
                                    @foreach ($company->animes as $anime)
                                        <tr>
                                            <td><a
                                                    href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                            </td>
                                            <td>
                                                {{ $anime->year }}年{{ $anime->coor_label }}クール
                                            </td>
                                            <td>{{ $anime->median }}</td>
                                            <td>{{ $anime->count }}</td>
                                            @auth
                                                <td>{{ $anime->userReview->score ?? '' }}</td>
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        該当する会社がありませんでした。
                    @endif
                </section>
            @break

            @case('user')
                <section class="user_search_result">
                    <h2>検索結果 user:{{ $search_word }}</h2>
                    @if (!$search_results->isEmpty())
                        <ul>
                            @foreach ($search_results as $user)
                                <li>
                                    <a href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        該当するユーザーがいませんでした。
                    @endif
                </section>
            @break
        @endswitch
        @if (!$search_results->onFirstPage())
            <a
                href="{{ $search_results->appends(request()->query())->previousPageUrl() }}">前へ</a>
        @endif
        @for ($i = 1; $i <= $search_results->lastPage(); $i++)
            @if ($search_results->currentPage() == $i)
                {{ $i }}
            @else
                <a
                    href="{{ $search_results->appends(request()->query())->url($i) }}">{{ $i }}</a>
            @endif
        @endfor
        @if ($search_results->hasMorePages())
            <a
                href="{{ $search_results->appends(request()->query())->nextPageUrl() }}">次へ</a>
        @endif
        {{ $search_results->currentPage() }}/{{ $search_results->lastPage() }}ページ
    </article>
@endsection
