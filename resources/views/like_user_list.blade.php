@extends('layout')

@section('title')
    <title>{{ $user->uid }}さんのお気に入りユーザー AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>{{ $user->uid }}さんのお気に入りユーザー</h2>
    <strong>{{ $user->uid }}</strong><br>
    <a href=" {{ route('user.statistics', ['uid' => $user->uid]) }} ">統計表</a>
    <h3>お気に入りユーザー</h3>
    <div id="like_users">
        <table id="like_users_table">
            <tbody>
                <tr>
                    <th>ユーザーID</th>
                    <th>twitter</th>
                    <th>誕生年</th>
                    <th>性別</th>
                    <th>最終データ登録日</th>
                </tr>
                @foreach ($like_users as $like_user)
                    <tr>
                        <td><a href="{{ route('user', ['uid' => $like_user->uid]) }}">{{ $like_user->uid }}</a>
                        </td>
                        <td>{{ $like_user->twitter ?? '-' }}</td>
                        <td>{{ $like_user->birth ?? '-' }}</td>
                        <td>
                            {{ $like_user->sex_label }}
                        </td>
                        <td>
                            {{ $like_user->userReviews->isEmpty()? '-': $like_user->userReviews->first()->updated_at }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection
