@extends('layout')

@section('title')
    <title>
        会社ランキング AnimeScape -アニメ批評空間-</title>
    <link rel="canonical" href="https://www.animescape.link/statistics_index/company">
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('company_statistics') }}
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <article class="company_statistics">
        <h1>会社ランキング
        </h1>
        <h2>検索条件変更</h2>
        <form action="{{ route('company_statistics.show') }}" class="search_parameters_form" method="GET">
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
            得点数が<input type="number" name="count" class="count" value="{{ $count ?? 0 }}" style="width:60px;">
            以上のアニメを制作した会社で
            <select name="category" class="category">
                <option value="score_median">中央値</option>
                <option value="score_average" {{ $category == 'score_average' ? 'selected' : '' }}>平均値</option>
                <option value="animes_count" {{ $category == 'animes_count' ? 'selected' : '' }}>制作数</option>
                <option value="score_count" {{ $category == 'score_count' ? 'selected' : '' }}>総得点数</option>
                <option value="score_users_count" {{ $category == 'score_users_count' ? 'selected' : '' }}>総得点ユーザー数
                </option>
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
        <div class="table-responsive">
            <table class="company_ranking_table">
                <tbody>
                    <tr>
                        <th>順位</th>
                        <th>会社名</th>
                        <th>制作数</th>
                        <th>中央値</th>
                        <th>平均値</th>
                        <th>総得点数</th>
                        <th>総得点ユーザー数</th>
                    </tr>
                    @foreach ($companies as $company)
                        <tr>
                            <td>{{ $companies->firstItem() + $loop->iteration - 1 }}</td>
                            <td><a
                                    href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                            </td>
                            <td>{{ $company->animes_count }}</td>
                            <td>{{ $company->score_median }}</td>
                            <td>{{ $company->score_average }}</td>
                            <td>{{ $company->score_count }}</td>
                            <td>{{ $company->score_users_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (!$companies->onFirstPage())
            <a href="{{ $companies->appends(request()->query())->previousPageUrl() }}">前へ</a>
        @endif
        @for ($i = 1; $i <= $companies->lastPage(); $i++)
            @if ($companies->currentPage() == $i)
                {{ $i }}
            @else
                <a href="{{ $companies->appends(request()->query())->url($i) }}">{{ $i }}</a>
            @endif
        @endfor
        @if ($companies->hasMorePages())
            <a href="{{ $companies->appends(request()->query())->nextPageUrl() }}">次へ</a>
        @endif
        {{ $companies->currentPage() }}/{{ $companies->lastPage() }}ページ
    </article>
@endsection
