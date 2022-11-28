@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴済みアニメリスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="watch_anime_list">
        <h1>{{ $user->name }}さんの視聴済みアニメリスト</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>視聴済みアニメリスト</h2>
        <table class="watch_anime_list_table">
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
                    <th>視聴登録日</th>
                </tr>
                @foreach ($watch_anime_list as $watch_anime)
                    <tr>
                        <td><a
                                href="{{ route('anime.show', ['anime_id' => $watch_anime->id]) }}">{{ $watch_anime->title }}</a>
                        </td>
                        <td>
                            @foreach ($watch_anime->companies as $company)
                                <a
                                    href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                            @endforeach
                        </td>
                        <td>
                            {{ $watch_anime->year }}年{{ $watch_anime->coor_label }}クール
                        </td>
                        <td>{{ $watch_anime->number_of_episode }}</td>
                        <td>{{ $watch_anime->median }}</td>
                        <td>{{ $watch_anime->average }}</td>
                        <td>{{ $watch_anime->stdev }}</td>
                        <td>{{ $watch_anime->count }}</td>
                        <td>{{ $watch_anime->userReview->score }}</td>
                        <td>{{ $watch_anime->userReview->watch_timestamp }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
