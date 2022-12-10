@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの得点を付けたアニメリスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="score_anime_list">
        <h1>{{ $user->name }}さんの得点を付けたアニメリスト({{ !is_null($year) ? $year . '年' : '' }}{{ (is_null($year) && is_null($coor) ? '全期間' : '') }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>得点を付けたアニメリスト({{ !is_null($year) ? $year . '年' : '' }}{{ (is_null($year) && is_null($coor) ? '全期間' : '') }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})</h2>
        <form action="{{ route('user_score_anime_list.show', ['user_id' => $user->id]) }}"
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
                href="{{ route('user_score_anime_list.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
        </form>
        <div class="table-responsive">
            <table class="score_anime_list_table">
                <tbody>
                    <tr>
                        <th>アニメ名</th>
                        <th>制作会社</th>
                        <th>放送クール</th>
                        <th>話数</th>
                        <th>中央値</th>
                        <th>平均値</th>
                        <th>標準偏差</th>
                        <th>得点数</th>
                        <th>つけた得点</th>
                        <th>視聴済み</th>
                        <th>視聴登録日</th>
                    </tr>
                    @foreach ($score_anime_list as $score_anime)
                        <tr>
                            <td><a
                                    href="{{ route('anime.show', ['anime_id' => $score_anime->id]) }}">{{ $score_anime->title }}</a>
                            </td>
                            <td>
                                @foreach ($score_anime->companies as $company)
                                    <a
                                        href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                @endforeach
                            </td>
                            <td>
                                {{ $score_anime->year }}年{{ $score_anime->coor_label }}クール
                            </td>
                            <td>{{ $score_anime->number_of_episode }}</td>
                            <td>{{ $score_anime->median }}</td>
                            <td>{{ $score_anime->average }}</td>
                            <td>{{ $score_anime->stdev }}</td>
                            <td>{{ $score_anime->count }}</td>
                            <td>{{ $score_anime->userReview->score }}</td>
                            <td>{{ $score_anime->userReview->watch == 1 ? '済' : '' }}</td>
                            <td>{{ $score_anime->userReview->watch_timestamp }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
