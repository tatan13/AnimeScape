@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りユーザー AnimeScape</title>
@endsection

@section('main')
    <article class="like_user_list">
        <h2>{{ $user->name }}さんのお気に入りユーザー</h2>
        <strong>{{ $user->name }}</strong><br>
        <a href=" {{ route('user_statistics.show', ['user_name' => $user->name]) }} ">統計表</a>
        <h3>お気に入りユーザー</h3>
        <table class="like_user_list_table">
            <tbody>
                <tr>
                    <th>ユーザーID</th>
                    <th>twitter</th>
                    <th>誕生年</th>
                    <th>性別</th>
                    <th>最終データ登録日</th>
                </tr>
                @foreach ($like_user_list as $like_user)
                    <tr>
                        <td><a
                                href="{{ route('user.show', ['user_name' => $like_user->name]) }}">{{ $like_user->name }}</a>
                        </td>
                        <td>{{ $like_user->twitter ?? '-' }}</td>
                        <td>{{ $like_user->birth ?? '-' }}</td>
                        <td>
                            {{ $like_user->sex_label }}
                        </td>
                        <td>
                            {{ $like_user->latestUserReviewUpdatedAt->updated_at ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
