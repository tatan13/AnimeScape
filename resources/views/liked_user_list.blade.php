@extends('layout')

@section('title')
    <title>{{ $user->uid }}さんの被お気に入りユーザー AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>{{ $user->uid }}さんの被お気に入りユーザー</h2>
            <strong>{{ $user->uid }}</strong>
            <h3>被お気に入りユーザー</h3>
            <div id="liked_users">
                <table id="liked_users_table">
                    <tbody>
                        <tr>
                            <th>ユーザーID</th>
                            <th>twitter</th>
                            <th>誕生年</th>
                            <th>性別</th>
                            <th>最終データ登録日</th>
                        </tr>
                        @foreach ($liked_users as $liked_user)
                            <tr>
                                <td><a href="{{ route('user', ['uid' => $liked_user->user->uid]) }}">{{ $liked_user->user->uid }}</a></td>
                                <td>{{ $liked_user->user->twitter ?? '-' }}</td>
                                <td>{{ $liked_user->user->birth ?? '-' }}</td>
                                <td>
                                    {{ $liked_user->user->sex_label }}
                                </td>
                                <td>
                                    {{ $liked_user->user->user_reviews()->get()->isEmpty() ? '-' : $liked_user->user->user_reviews->last()->created_at }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
