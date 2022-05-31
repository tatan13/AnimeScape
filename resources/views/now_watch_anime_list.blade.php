@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴中アニメリスト AnimeScape</title>
@endsection

@section('main')
    <article class="now_watch_anime_list">
        <h2>{{ $user->name }}さんの視聴中アニメリスト</h2>
        <strong>{{ $user->name }}</strong>
        <h3>視聴中アニメリスト</h3>
        <table class="now_watch_anime_list_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>制作会社</th>
                    <th>@sortablelink('unionYearCoor', '放送クール')</th>
                    <th>@sortablelink('number_of_episode', '話数')</th>
                    <th>@sortablelink('median', '中央値')</th>
                    <th>@sortablelink('average', '平均値')</th>
                    <th>@sortablelink('count', 'データ数')</th>
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
                        <td>{{ $now_watch_anime->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection