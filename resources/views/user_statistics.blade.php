@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りユーザーの統計表 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="user_statistics">
        <h1>{{ $user->name }}さんのお気に入りユーザーの統計表</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>表示設定</h2>
        <form action="{{ route('user_statistics.show', ['user_id' => $user->id]) }}" class="search_parameters_form"
            method="get">
            @csrf
            中央値
            <input type="number" name="median" class="median" value="{{ $median ?? 70 }}" style="width:50px;">以上<br>
            得点数
            <input type="number" name="count" class="count" value="{{ $count ?? 0 }}" style="width:60px;">以上<br>
            放送時期
            <input type="number" name="bottom_year" class="bottom_year" value="{{ $bottom_year ?? 1900 }}"
                style="width:70px;">～
            <input type="number" name="top_year" class="top_year" value="{{ $top_year ?? 2100 }}" style="width:70px;"><br>
            <input type="submit" value="絞り込み">
            <a href="{{ route('user_statistics.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
        </form>
        <h2>統計表</h2>
        <div class="table-responsive">
            <table class="user_statistics_table">
                <tbody>
                    <tr>
                        <th>アニメ名</th>
                        <th>放送クール</th>
                        <th>中央値</th>
                        <th>得点数</th>
                        <th>入力済み</th>
                        <th>入力ユーザー</th>
                    </tr>
                    @foreach ($user_anime_statistics as $anime)
                        <tr>
                            <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                            </td>
                            <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                            <td>{{ $anime->median }}</td>
                            <td>
                                {{ $anime->count }}
                            </td>
                            <td>
                                {{ $anime->isContainMe == 1 ? '済' : '' }}
                            </td>
                            <td>
                                <ul class="list-inline d-inline">
                                    @foreach ($anime->userReviews as $user_review)
                                        <li class="d-inline">
                                            <span style="font-size: 50%;">{{ $user_review->score }}</span>
                                            <a href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
