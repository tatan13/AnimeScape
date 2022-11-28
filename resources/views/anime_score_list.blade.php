@extends('layout')

@section('title')
    <title>{{ $anime->title }}の得点を付けたユーザーリスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="anime_score_list">
        <h1>
            <a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>の得点を付けたユーザーリスト
        </h1>
        <h2>得点を付けたユーザーリスト</h2>
        <table class="anime_review_table">
            <tbody>
                <tr>
                    <th>登録日</th>
                    <th>得点</th>
                    <th>ユーザー</th>
                </tr>
                @foreach ($anime->userReviews as $user_review)
                    <tr>
                        <td>
                            {{ $user_review->created_at }}
                        </td>
                        <td>
                            {{ $user_review->score }}
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
