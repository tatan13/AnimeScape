@extends('layout')

@section('title')
    <title>
        {{ $user->name }}さんの感想リスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="comment_anime_list">
        <h1>{{ $user->name }}さんの感想リスト</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>感想リスト</h2>
        @foreach ($comment_anime_list as $comment_anime)
            @if ($loop->iteration % 2 == 0)
                <div class="comment_even">
                @else
                    <div class="comment_odd">
            @endif
            @if (!is_null($comment_anime->userReview->score))
                <strong>{{ $comment_anime->userReview->score }}点</strong>
            @endif
            <a href="{{ route('anime.show', ['anime_id' => $comment_anime->id]) }}">{{ $comment_anime->title }}</a><br>
            {{ $comment_anime->userReview->one_word_comment }}
            @if (!is_null($comment_anime->userReview->long_word_comment))
                <a href="{{ route('user_anime_comment.show', ['user_review_id' => $comment_anime->userReview->id]) }}">→長文感想({{ mb_strlen($comment_anime->userReview->long_word_comment) }}文字)
                    @if ($comment_anime->userReview->spoiler == true)
                        (ネタバレ注意)
                    @endif
                </a>
            @endif
            <br>
            {{ $comment_anime->userReview->comment_timestamp }} <a
                href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
            </div>
        @endforeach
    </article>
@endsection
