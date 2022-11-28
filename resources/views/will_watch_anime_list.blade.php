@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴予定表 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="will_watch_anime_list">
        <h1>{{ $user->name }}さんの視聴予定表</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>視聴予定表</h2>
        <table class="will_watch_anime_list_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>制作会社</th>
                    <th>@sortablelink('unionYearCoor', '放送クール')</th>
                    <th>@sortablelink('number_of_episode', '話数')</th>
                    <th>@sortablelink('median', '中央値')</th>
                    <th>@sortablelink('average', '平均値')</th>
                    <th>@sortablelink('count', '得点数')</th>
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
