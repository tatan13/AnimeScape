@extends('layout')

@section('title')
    <title>{{ $user_review->user->name }}さんの「{{ $user_review->anime->title }}」の感想 AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="anime_comment">
        <h2>
            <a href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
            さんの「
            <a
                href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
            」の感想
        </h2>
        <section class="user_anime_comment">
            <h3>
                @if (!is_null($user_review->score))
                    {{ $user_review->score }}点
                @endif
                <a
                    href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
            </h3>
            <p class="text-info">{{ $user_review->one_word_comment }}</p>
            <p>{{ $user_review->long_word_comment }}</p>
            <p>{{ $user_review->comment_timestamp }}</p>
        </section>
    </article>
@endsection
