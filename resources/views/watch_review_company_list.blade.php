@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの制作会社別視聴数 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="watch_review_company_list">
        <h1>{{ $user->name }}さんの制作会社別視聴数({{ !is_null($year) ? $year . '年' : '' }}{{ (is_null($year) && is_null($coor) ? '全期間' : '') }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})</h1>
        <section class="watch_review_company_list">
            <h2>制作会社別視聴数({{ !is_null($year) ? $year . '年' : '' }}{{ (is_null($year) && is_null($coor) ? '全期間' : '') }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})</h2>
            <form action="{{ route('user_watch_review_company_list.show', ['user_id' => $user->id]) }}" class="search_parameters_form"
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
                    href="{{ route('user_watch_review_company_list.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
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
                            <td>{{ $company->animes->average('userReview.score') }}</td>
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
        </section>
    </article>
    </div>
@endsection
