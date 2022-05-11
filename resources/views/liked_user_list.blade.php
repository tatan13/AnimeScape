@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの被お気に入りユーザー AnimeScape</title>
@endsection

@section('main')
    <article class="liked_user_list">
        <h2>{{ $user->name }}さんの被お気に入りユーザー</h2>
        <strong>{{ $user->name }}</strong>
        <h3>被お気に入りユーザー</h3>
        <table class="liked_user_list_table">
            <tbody>
                <tr>
                    <th>ユーザーID</th>
                    <th>twitter</th>
                    <th>誕生年</th>
                    <th>性別</th>
                    <th>最終データ登録日</th>
                </tr>
                @foreach ($liked_user_list as $liked_user)
                    <tr>
                        <td><a
                                href="{{ route('user.show', ['user_id' => $liked_user->id]) }}">{{ $liked_user->name }}</a>
                        </td>
                        <td>{{ $liked_user->twitter ?? '-' }}</td>
                        <td>{{ $liked_user->birth ?? '-' }}</td>
                        <td>
                            {{ $liked_user->sex_label }}
                        </td>
                        <td>
                            {{ $liked_user->latestUserReviewUpdatedAt->updated_at ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
