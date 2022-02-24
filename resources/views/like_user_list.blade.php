@extends('layout')

@section('title')
    <title>{{ $user->uid }}さんのお気に入りユーザー AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>{{ $user->uid }}さんのお気に入りユーザー</h2>
            <strong>{{ $user->uid }}</strong>
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
                                <td><a
                                        href="{{ route('user', ['uid' => $like_user->liked_user->uid]) }}">{{ $like_user->liked_user->uid }}</a>
                                </td>
                                <td>{{ $like_user->liked_user->twitter ?? '-' }}</td>
                                <td>{{ $like_user->liked_user->birth ?? '-' }}</td>
                                <td>
                                    @if(is_null($like_user->liked_user->sex))
                                        -
                                    @elseif($like_user->liked_user->sex)
                                        男性
                                    @else
                                        女性
                                    @endif
                                </td>
                                <td>
                                    {{ $like_user->liked_user->user_reviews()->get()->last()->created_at }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
