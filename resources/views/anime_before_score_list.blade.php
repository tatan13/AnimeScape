@extends('layout')

@section('title')
    <title>{{ $anime->title }}の視聴完了前得点を付けたユーザーリスト AnimeScape -アニメ批評空間-</title>
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

@section('main')
    <article class="anime_score_list">
        <h2>
            <a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>の視聴完了前得点を付けたユーザーリスト
        </h2>
        <h3>視聴完了前得点を付けたユーザーリスト</h3>
        <table class="anime_review_table">
            <tbody>
                <tr>
                    <th>登録日</th>
                    <th>視聴前得点</th>
                    <th>ユーザー</th>
                </tr>
                @foreach ($anime->userReviews as $user_review)
                    <tr>
                        <td>
                            {{ $user_review->before_score_timestamp }}
                        </td>
                        <td>
                            {{ $user_review->before_score }}
                        </td>
                        <td>
                            <a
                                href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
