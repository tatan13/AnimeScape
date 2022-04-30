@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りユーザーの統計表 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>{{ $user->name }}さんのお気に入りユーザーの統計表</h2>
    <strong>{{ $user->name }}</strong>
    <h3>表示設定</h3>
    <form action="{{ route('user_statistics.show', ['user_name' => $user->name]) }}" method="get">
        @csrf
        中央値
        <input type="number" name="median" value="{{ $median ?? 70 }}" style="width:50px;">以上<br>
        データ数
        <input type="number" name="count" value="{{ $count ?? 0 }}" style="width:60px;">以上<br>
        放送時期
        <input type="number" name="bottom_year" value="{{ $bottom_year ?? 1900 }}" style="width:70px;">～
        <input type="number" name="top_year" value="{{ $top_year ?? 2100 }}" style="width:70px;"><br>
        <input type="submit" value="絞り込み">
        <a href="{{ route('user_statistics.show', ['user_name' => $user->name]) }}">絞り込み解除</a>
    </form>
    <h3>統計表</h3>
    <div id="user_statistics">
        <table id="user_statistics_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>データ数</th>
                    <th>入力済み</th>
                    <th>入力ユーザー</th>
                </tr>
                @foreach ($user_anime_statistics as $anime)
                    <tr>
                        <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
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
                            <ul>
                                @foreach ($anime->userReviews as $user_review)
                                    <li>
                                        <span class="score">{{ $user_review->score }}</span>
                                        <a href="{{ route('user.show', ['user_name' => $user_review->user->name]) }}">{{ $user_review->user->name }}
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
    </div>
    </div>
@endsection
