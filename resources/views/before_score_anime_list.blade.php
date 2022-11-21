@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴完了前得点を付けたアニメリスト AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="before_score_anime_list">
        <h2>{{ $user->name }}さんの視聴完了前得点を付けたアニメリスト</h2>
        <strong>{{ $user->name }}</strong>
        <h3>視聴完了前得点を付けたアニメリスト</h3>
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
    </article>
@endsection
