@extends('layout')

@section('title')
    <title>
        {{ !is_null($year) ? $year . '年' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }}アニメランキング
        AnimeScape -アニメ批評空間-</title>
    <link rel="canonical" href="https://www.animescape.link/statistics_index/anime">
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('anime_statistics') }}
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
    <article class="anime_statistics">
        <h1>{{ !is_null($year) ? $year . '年' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }}アニメランキング
        </h1>
        <h2>検索条件変更</h2>
        <form action="{{ route('anime_statistics.show') }}" class="search_parameters_form" method="GET">
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
            </select>の
            得点数が
            <input type="number" name="count" class="count" value="{{ $count ?? 0 }}" style="width:60px;">
            以上のアニメで
            <select name="category" class="category">
                <option value="median">中央値</option>
                <option value="average" {{ $category == 'average' ? 'selected' : '' }}>平均値</option>
                <option value="count" {{ $category == 'count' ? 'selected' : '' }}>得点数</option>
            </select>
            順に<input type="submit" value="絞り込む">
        </form>
        <section class="adsense">
            <h2>広告</h2>
            @if (env('APP_ENV') == 'production')
                @include('layout.horizontal_adsense')
            @endif
        </section>
        <h2>ランキング</h2>
        <form action="{{ route('anime_statistics.show') }}" name="previous" class="d-inline" method="get">
            @csrf
            <input type="hidden" name="category" class="category" value="{{ $category ?? 'median' }}">
            <input type="hidden" name="count" class="count" value="{{ $count ?? 0 }}">
            <input type="hidden" name="year" class="year"
                value="{{ $coor == 1 || is_null($coor) ? $year - 1 : $year }}">
            @if (!is_null($coor))
                <input type="hidden" name="coor" class="coor" value="{{ $coor == 1 ? 4 : $coor - 1 }}">
            @endif
            <a href="javascript:previous.submit()">{{ is_null($year) ? '' : (is_null($coor) ? '前の年へ' : '前クールへ') }}</a>
        </form>
        <form action="{{ route('anime_statistics.show') }}" name="next" class="d-inline" method="get">
            @csrf
            <input type="hidden" name="category" class="category" value="{{ $category ?? 'median' }}">
            <input type="hidden" name="count" class="count" value="{{ $count ?? 0 }}">
            <input type="hidden" name="year" class="year"
                value="{{ $coor == 4 || is_null($coor) ? $year + 1 : $year }}">
            @if (!is_null($coor))
                <input type="hidden" name="coor" class="coor" value="{{ $coor == 4 ? 1 : $coor + 1 }}">
            @endif
            <a href="javascript:next.submit()">{{ is_null($year) ? '' : (is_null($coor) ? '次の年へ' : '次クールへ') }}</a>
        </form>
        <div class="table-responsive">
            <table class="anime_ranking_table">
                <tbody>
                    <tr>
                        <th>順位</th>
                        <th>アニメ名</th>
                        <th>制作会社</th>
                        <th>放送媒体</th>
                        <th>@sortablelink('unionYearCoor', 'クール')</th>
                        <th>@sortablelink('number_of_episode', '話数')</th>
                        <th>@sortablelink('median', '中央値')</th>
                        <th>@sortablelink('average', '平均値')</th>
                        <th>@sortablelink('stdev', '標準偏差')</th>
                        <th>@sortablelink('count', '得点数')</th>
                        @auth
                            <th>つけた得点</th>
                        @endauth
                    </tr>
                    @foreach ($animes as $anime)
                        <tr>
                            <td>{{ $animes->firstItem() + $loop->iteration - 1 }}</td>
                            <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                            </td>
                            <td>
                                @foreach ($anime->companies as $company)
                                    <a
                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                @endforeach
                            </td>
                            <td>{{ $anime->media_category_label }}</td>
                            <td>{{ $anime->year }}年{{ $anime->coor_label }}</td>
                            <td>{{ $anime->number_of_episode }}</td>
                            <td>{{ $anime->median }}</td>
                            <td>{{ $anime->average }}</td>
                            <td>{{ $anime->stdev }}</td>
                            <td>{{ $anime->count }}</td>
                            @auth
                                <td>{{ $anime->userReview->score ?? '' }}</td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (!$animes->onFirstPage())
            <a href="{{ $animes->appends(request()->query())->previousPageUrl() }}">前へ</a>
        @endif
        @for ($i = 1; $i <= $animes->lastPage(); $i++)
            @if ($animes->currentPage() == $i)
                {{ $i }}
            @else
                <a href="{{ $animes->appends(request()->query())->url($i) }}">{{ $i }}</a>
            @endif
        @endfor
        @if ($animes->hasMorePages())
            <a href="{{ $animes->appends(request()->query())->nextPageUrl() }}">次へ</a>
        @endif
        {{ $animes->currentPage() }}/{{ $animes->lastPage() }}ページ
    </article>
@endsection
