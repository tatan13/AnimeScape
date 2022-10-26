@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの得点を付けたアニメリスト AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="score_anime_list">
        <h2>{{ $user->name }}さんの得点を付けたアニメリスト</h2>
        <strong>{{ $user->name }}</strong>
        <h3>得点を付けたアニメリスト</h3>
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
    </article>
@endsection
