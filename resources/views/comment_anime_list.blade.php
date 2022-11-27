@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの感想リスト AnimeScape -アニメ批評空間-</title>
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
    <article class="comment_anime_list">
        <h2>{{ $user->name }}さんの感想リスト</h2>
        <div class="title">{{ $user->name }}</div>
        <h3>感想リスト</h3>
        @foreach ($comment_anime_list as $comment_anime)
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
            <p>
                {{ $comment_anime->userReview->comment_timestamp }} <a
                    href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
            </p>
            <hr>
        @endforeach
    </article>
@endsection
