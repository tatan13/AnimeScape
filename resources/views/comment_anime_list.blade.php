@extends('layout')

@section('title')
    <title>
        {{ $user->name }}さんの感想リスト AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <article class="comment_anime_list">
        <h1>{{ $user->name }}さんの感想リスト({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
        </h1>
        <div class="title">{{ $user->name }}</div>
        <h2>感想リスト({{ !is_null($year) ? $year . '年' : '' }}{{ is_null($year) && is_null($coor) ? '全期間' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }})
        </h2>
        <form action="{{ route('user_comment_anime_list.show', ['user_id' => $user->id]) }}" class="search_parameters_form"
            name="coor_score_animelist" method="get">
            @csrf
            <select name="year" class="year">
                <option value="">-</option>
                @include('layout/select_year')
            </select>
            年
            <select name="coor" class="coor">
                <option value="">-</option>
                <option value="1" {{ is_null($coor ?? null) ? '' : ($coor == 1 ? 'selected' : '') }}>冬
                </option>
                <option value="2" {{ is_null($coor ?? null) ? '' : ($coor == 2 ? 'selected' : '') }}>春
                </option>
                <option value="3" {{ is_null($coor ?? null) ? '' : ($coor == 3 ? 'selected' : '') }}>夏
                </option>
                <option value="4" {{ is_null($coor ?? null) ? '' : ($coor == 4 ? 'selected' : '') }}>秋
                </option>
            </select>
            <input type="submit" value="絞り込み"> <a
                href="{{ route('user_comment_anime_list.show', ['user_id' => $user->id]) }}">絞り込み解除</a>
        </form>
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
