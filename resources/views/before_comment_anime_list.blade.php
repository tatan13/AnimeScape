@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴完了前一言感想リスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">

@section('main')
    <article class="before_comment_anime_list">
        <h1>{{ $user->name }}さんの視聴完了前一言感想リスト</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>感想リスト</h2>
        @foreach ($before_comment_anime_list as $before_comment_anime)
            @if (!is_null($before_comment_anime->userReview->before_score))
                <strong>{{ $before_comment_anime->userReview->before_score }}点</strong>
            @endif
            <a
                href="{{ route('anime.show', ['anime_id' => $before_comment_anime->id]) }}">{{ $before_comment_anime->title }}</a><br>
            {{ $before_comment_anime->userReview->before_comment }}
            <p>
                {{ $before_comment_anime->userReview->before_comment_timestamp }} <a
                    href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
            </p>
            <hr>
        @endforeach
    </article>
@endsection
