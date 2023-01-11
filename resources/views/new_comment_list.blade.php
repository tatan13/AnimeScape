@extends('layout')

@section('title')
    <title>
        新着一言感想一覧AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@if (env('APP_ENV') == 'production')
    @section('main_adsense_smartphone')
        @include('layout.horizontal_adsense_smartphone')
    @endsection
@endif

@section('main')
    <article class="index">
        <h1>新着感想一覧</h1>
        <section class="new_comment">
            <h2>新着感想</h2>
            @foreach ($user_reviews_latest_comment as $user_review)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
                @if (!is_null($user_review->score))
                    <strong>{{ $user_review->score }}点</strong>
                @endif
                <a
                    href="{{ route('anime.show', ['anime_id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
                <br>
                {{ $user_review->one_word_comment }}
                @if (!is_null($user_review->long_word_comment))
                    <a href="{{ route('user_anime_comment.show', ['user_review_id' => $user_review->id]) }}">→長文感想({{ mb_strlen($user_review->long_word_comment) }}文字)
                        @if ($user_review->spoiler == true)
                            (ネタバレ注意)
                        @endif
                    </a>
                @endif
                <br>
                {{ $user_review->comment_timestamp }} <a
                    href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
                </div>
            @endforeach
            @if (!$user_reviews_latest_comment->onFirstPage())
                <a href="{{ $user_reviews_latest_comment->appends(request()->query())->previousPageUrl() }}">前へ</a>
            @endif
            {{ $user_reviews_latest_comment->currentPage() }}/{{ $user_reviews_latest_comment->lastPage() }}ページ
            @if ($user_reviews_latest_comment->hasMorePages())
                <a href="{{ $user_reviews_latest_comment->appends(request()->query())->nextPageUrl() }}">次へ</a>
            @endif
        </section>
        @if (env('APP_ENV') == 'production')
            @include('layout.horizontal_multiplex_adsense')
        @endif
    </article>
@endsection
