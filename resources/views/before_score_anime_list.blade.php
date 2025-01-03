@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴完了前得点を付けたアニメリスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <article class="before_score_anime_list">
        <h1>{{ $user->name }}さんの視聴完了前得点を付けたアニメリスト({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
        </h1>
        <div class="title">{{ $user->name }}</div>
        <section>
            <h2>視聴完了前得点を付けたアニメリスト({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
            </h2>
            <form action="{{ route('user_before_score_anime_list.show', ['user_id' => $user->id]) }}"
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
                    href="{{ route('user_before_score_anime_list.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
            </form>
            <div class="table-responsive">
                <table class="before_score_anime_list_table">
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
                            <th>視聴中</th>
                            <th>得点登録日</th>
                        </tr>
                        @foreach ($before_score_anime_list as $before_score_anime)
                            <tr>
                                <td><a
                                        href="{{ route('anime.show', ['anime_id' => $before_score_anime->id]) }}">{{ $before_score_anime->title }}</a>
                                </td>
                                <td>
                                    @foreach ($before_score_anime->companies as $company)
                                        <a
                                            href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $before_score_anime->year }}年{{ $before_score_anime->coor_label }}クール
                                </td>
                                <td>{{ $before_score_anime->number_of_episode }}</td>
                                <td>{{ $before_score_anime->before_median }}</td>
                                <td>{{ $before_score_anime->before_average }}</td>
                                <td>{{ $before_score_anime->before_stdev }}</td>
                                <td>{{ $before_score_anime->before_count }}</td>
                                <td>{{ $before_score_anime->userReview->before_score }}</td>
                                <td>{{ $before_score_anime->userReview->now_watch == 1 ? '○' : '' }}</td>
                                <td>{{ $before_score_anime->userReview->before_score_timestamp }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <h2>視聴完了前得点とアニメの対応表({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
            </h2>
            <form action="{{ route('user_before_score_anime_list.show', ['user_id' => $user->id]) }}"
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
                    href="{{ route('user_before_score_anime_list.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
            </form>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>視聴完了前得点</th>
                            <th>アニメ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>100</th>
                            <td>
                                <ul class="list-inline d-inline">
                                    @foreach ($before_score_anime_list->where('userReview.before_score', 100)->sortByDesc('userReview.before_score') as ${'before_score_100_anime'})
                                        <li class="d-inline">
                                            <span
                                                style="font-size: 50%;">{{ ${'before_score_100_anime'}->userReview->before_score }}</span>
                                            <a
                                                href="{{ route('anime.show', ['anime_id' => ${'before_score_100_anime'}->id]) }}">{{ ${'before_score_100_anime'}->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @for ($i = 95; $i > 0; $i = $i - 5)
                            <tr>
                                <th>{{ $i }}</th>
                                <td>
                                    <ul class="list-inline d-inline">
                                        @foreach ($before_score_anime_list->whereBetween('userReview.before_score', [$i, $i + 4])->sortByDesc('userReview.before_score') as ${'before_score_' . $i . '_anime'})
                                            <li class="d-inline">
                                                <span
                                                    style="font-size: 50%;">{{ ${'before_score_' . $i . '_anime'}->userReview->before_score }}</span>
                                                <a
                                                    href="{{ route('anime.show', ['anime_id' => ${'before_score_' . $i . '_anime'}->id]) }}">{{ ${'before_score_' . $i . '_anime'}->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endfor
                        <tr>
                            <th>0</th>
                            <td>
                                <ul class="list-inline d-inline">
                                    @foreach ($before_score_anime_list->where('userReview.before_score', 0)->sortByDesc('userReview.before_score') as ${'before_score_0_anime'})
                                        <li class="d-inline">
                                            <span
                                                style="font-size: 50%;">{{ ${'before_score_0_anime'}->userReview->before_score }}</span>
                                            <a
                                                href="{{ route('anime.show', ['anime_id' => ${'before_score_0_anime'}->id]) }}">{{ ${'before_score_' . $i . '_anime'}->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </article>
@endsection
