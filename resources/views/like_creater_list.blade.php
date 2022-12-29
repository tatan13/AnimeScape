@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りクリエイター AnimeScape -アニメ批評空間-</title>
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
    <article class="like_creater_list">
        <h1>{{ $user->name }}さんのお気に入りクリエイター</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>お気に入りクリエイター</h2>
        <ul>
            @foreach ($like_creater_list as $creater)
                <li>
                    <a href="{{ route('creater.show', ['creater_id' => $creater->id]) }}">{{ $creater->name }}</a>
                </li>
            @endforeach
        </ul>
    </article>
@endsection
