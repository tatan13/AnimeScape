@extends('layout')

@section('title')
    <title>{{ $user_review->user->name }}さんの「{{ $user_review->anime->title }}」の視聴完了前感想 AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('tweet_button')
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection

@section('main')
    <article class="anime_before_comment">
        <h1>
            <a href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
            さんの「
            <a href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
            」の視聴完了前感想
        </h1>
        <section class="user_anime_before_comment">
            <h2>
                @if (!is_null($user_review->before_score))
                    {{ $user_review->before_score }}点
                @endif
                <a
                    href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
            </h2>
            <p class="text-info">{{ $user_review->before_comment }}</p>
            <p>{!! nl2br(e($user_review->before_long_comment)) !!}</p>
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button"
                data-text="{{ $user_review->user->name }}さんの{{ $user_review->anime->title }}の視聴完了前感想{{ !is_null($user_review->before_score) ? '(' . $user_review->before_score . '点)' : '' }}{{ $user_review->before_comment_spoiler == true ? '(ネタバレあり)' : '' }}"
                data-url="{{ route('user_anime_before_comment.show', ['user_review_id' => $user_review->id]) }}"
                data-hashtags="AnimeScape" data-related="tatan_tech" data-show-count="false">Tweet</a>
            <p>{{ $user_review->before_comment_timestamp }}</p>
            <hr>
        </section>
    </article>
@endsection
