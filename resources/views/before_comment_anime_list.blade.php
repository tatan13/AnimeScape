@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴完了前一言感想リスト AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="before_comment_anime_list">
        <h2>{{ $user->name }}さんの視聴完了前一言感想リスト</h2>
        <strong>{{ $user->name }}</strong>
        <h3>感想リスト</h3>
        @foreach ($before_comment_anime_list as $before_comment_anime)
            @if (!is_null($before_comment_anime->userReview->before_score))
                <strong>{{ $before_comment_anime->userReview->before_score }}点</strong>
            @endif
            <a href="{{ route('anime.show', ['anime_id' => $before_comment_anime->id]) }}">{{ $before_comment_anime->title }}</a><br>
            {{ $before_comment_anime->userReview->before_comment }}
            <p>
                {{ $before_comment_anime->userReview->before_comment_timestamp }} <a
                    href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
            </p>
            <hr>
        @endforeach
    </article>
@endsection
