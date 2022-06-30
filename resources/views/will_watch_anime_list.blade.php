@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴予定表 AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="will_watch_anime_list">
        <h2>{{ $user->name }}さんの視聴予定表</h2>
        <strong>{{ $user->name }}</strong>
        <h3>視聴予定表</h3>
        <table class="will_watch_anime_list_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>制作会社</th>
                    <th>@sortablelink('unionYearCoor', '放送クール')</th>
                    <th>@sortablelink('number_of_episode', '話数')</th>
                    <th>@sortablelink('median', '中央値')</th>
                    <th>@sortablelink('average', '平均値')</th>
                    <th>@sortablelink('count', 'データ数')</th>
                    <th>視聴予定</th>
                </tr>
                @foreach ($will_watch_anime_list as $will_watch_anime)
                    <tr>
                        <td><a
                                href="{{ route('anime.show', ['anime_id' => $will_watch_anime->id]) }}">{{ $will_watch_anime->title }}</a>
                        </td>
                        <td>
                            @foreach ($will_watch_anime->companies as $company)
                                <a
                                    href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                            @endforeach
                        </td>
                        <td>
                            {{ $will_watch_anime->year }}年{{ $will_watch_anime->coor_label }}クール
                        </td>
                        <td>{{ $will_watch_anime->number_of_episode }}</td>
                        <td>{{ $will_watch_anime->median }}</td>
                        <td>{{ $will_watch_anime->average }}</td>
                        <td>{{ $will_watch_anime->count }}</td>
                        <td>{{ $will_watch_anime->userReview->will_watch_label }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
