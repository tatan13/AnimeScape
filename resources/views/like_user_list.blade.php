@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りユーザー AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <article class="like_user_list">
        <h1>{{ $user->name }}さんのお気に入りユーザー</h1>
        <div class="title">{{ $user->name }}</div>
        <a href=" {{ route('user_statistics.show', ['user_id' => $user->id]) }} ">統計表</a>
        <h2>お気に入りユーザー</h2>
        <div class="table-responsive">
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
                            <td><a href="{{ route('user.show', ['user_id' => $like_user->id]) }}">{{ $like_user->name }}</a>
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
        </div>
    </article>
@endsection
