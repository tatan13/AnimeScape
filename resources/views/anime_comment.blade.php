@extends('layout')

@section('title')
    <title>{{ $user_review->user->name }}さんの「{{ $user_review->anime->title }}」の感想 AnimeScape -アニメ批評空間-</title>
    @if (is_null($user_review->long_word_comment))
        <meta name="robots" content="noindex,nofollow">
    @endif
@endsection

@section('tweet_button')
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection

@section('main')
    <article class="anime_comment">
        <h1>
            <a href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
            さんの「
            <a href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
            」の感想
        </h1>
        <section class="user_anime_comment">
            <h2>
                @if (!is_null($user_review->score))
                    {{ $user_review->score }}点
                @endif
                <a
                    href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
            </h2>
            <p class="text-info">{{ $user_review->one_word_comment }}</p>
            <p>{!! nl2br(e($user_review->long_word_comment)) !!}</p>
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button"
                data-text="{{ $user_review->user->name }}さんの{{ $user_review->anime->title }}の感想{{ !is_null($user_review->score) ? '(' . $user_review->score . '点)' : '' }}{{ $user_review->spoiler == true ? '(ネタバレあり)' : '' }}"
                data-url="{{ route('user_anime_comment.show', ['user_review_id' => $user_review->id]) }}"
                data-hashtags="AnimeScape" data-related="tatan_tech" data-show-count="false">Tweet</a>
            <p>{{ $user_review->comment_timestamp }}</p>
            <hr>
        </section>
    </article>
@endsection
