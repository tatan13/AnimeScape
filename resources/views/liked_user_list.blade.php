@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの被お気に入りユーザー AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('title_adsense')
    @include('layout.horizontal_adsense')
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main_adsense_smartphone')
    @include('layout.horizontal_adsense_smartphone')
@endsection

@section('main')
    <article class="liked_user_list">
        <h2>{{ $user->name }}さんの被お気に入りユーザー</h2>
        <div class="title">{{ $user->name }}</div>
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
                        <td><a href="{{ route('user.show', ['user_id' => $liked_user->id]) }}">{{ $liked_user->name }}</a>
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
