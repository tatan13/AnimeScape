@extends('layout')

@section('title')
    <title>{{ $tag->name }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('tag', $tag) }}
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
    <article class="tag">
        <h1>{{ $tag->name }}</h1>
        <section class="tag_information">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <div class="title">{{ $tag->name }}</div>
            <p style="background-color: rgb(253, 253, 170);">{{ $tag->explanation }}</p>
            @if (Auth::check())
                <a href="{{ route('tag_review.show', ['tag_id' => $tag->id]) }}">このタグをアニメに一括で括り付ける</a>
            @else
                <a href="{{ route('tag_review.show', ['tag_id' => $tag->id]) }}">ログインしてこのタグをアニメに一括で括り付ける</a>
            @endif
            @can('isAdmin')
                <a href="{{ route('modify_tag_request.show', ['tag_id' => $tag->id]) }}">このタグの情報を変更する</a>
            @endcan
            <section class="adsense">
                <h2>広告</h2>
                @if (env('APP_ENV') == 'production')
                    @include('layout.horizontal_adsense')
                @endif
            </section>
            <h2>登録数の多い順</h2>
            @foreach ($animes as $anime)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
                <a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>(@foreach ($anime->companies as $company)
                    <a href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                @endforeach
                )(<a
                    href="{{ route('anime_statistics.show', ['year' => $anime->year, 'coor' => $anime->coor]) }}">{{ $anime->year }}年{{ $anime->coor_label }})</a><br>
                {{ $anime->tag_reviews_count }}件 中央値{{ $anime->tagReviews->median('score') }}点
                </div>
            @endforeach
            @if (env('APP_ENV') == 'production')
                @include('layout.horizontal_multiplex_adsense')
            @endif
        </section>
    </article>
@endsection
