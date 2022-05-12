@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴予定表 AnimeScape</title>
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
                    <th>会社名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>データ数</th>
                </tr>
                @foreach ($will_watch_anime_list as $will_watch_anime)
                    <tr>
                        <td><a
                                href="{{ route('anime.show', ['id' => $will_watch_anime->id]) }}">{{ $will_watch_anime->title }}</a>
                        </td>
                        <td>{{ $will_watch_anime->company }}</td>
                        <td>
                            {{ $will_watch_anime->year }}年{{ $will_watch_anime->coor_label }}クール
                        </td>
                        <td>{{ $will_watch_anime->median }}</td>
                        <td>{{ $will_watch_anime->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
