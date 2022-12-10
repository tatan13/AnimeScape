@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴中アニメリスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="now_watch_anime_list">
        <h1>{{ $user->name }}さんの視聴中アニメリスト({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
        </h1>
        <div class="title">{{ $user->name }}</div>
        <h2>視聴中アニメリスト({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
        </h2>
        <form action="{{ route('user_now_watch_anime_list.show', ['user_id' => $user->id]) }}" class="search_parameters_form"
            name="coor_score_animelist" method="get">
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
                href="{{ route('user_now_watch_anime_list.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
        </form>
        <div class="table-responsive">
            <table class="now_watch_anime_list_table">
                <tbody>
                    <tr>
                        <th>アニメ名</th>
                        <th>制作会社</th>
                        <th>@sortablelink('unionYearCoor', '放送クール')</th>
                        <th>@sortablelink('number_of_episode', '話数')</th>
                        <th>@sortablelink('median', '中央値')</th>
                        <th>@sortablelink('average', '平均値')</th>
                        <th>@sortablelink('stdev', '標準偏差')</th>
                        <th>@sortablelink('count', '得点数')</th>
                    </tr>
                    @foreach ($now_watch_anime_list as $now_watch_anime)
                        <tr>
                            <td><a
                                    href="{{ route('anime.show', ['anime_id' => $now_watch_anime->id]) }}">{{ $now_watch_anime->title }}</a>
                            </td>
                            <td>
                                @foreach ($now_watch_anime->companies as $company)
                                    <a
                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                @endforeach
                            </td>
                            <td>
                                {{ $now_watch_anime->year }}年{{ $now_watch_anime->coor_label }}クール
                            </td>
                            <td>{{ $now_watch_anime->number_of_episode }}</td>
                            <td>{{ $now_watch_anime->median }}</td>
                            <td>{{ $now_watch_anime->average }}</td>
                            <td>{{ $now_watch_anime->stdev }}</td>
                            <td>{{ $now_watch_anime->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
