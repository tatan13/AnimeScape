@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りクリエイター AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="like_creater_list">
        <h2>{{ $user->name }}さんのお気に入りクリエイター</h2>
        <strong>{{ $user->name }}</strong>
        <h3>お気に入りクリエイター</h3>
        <ul>
            @foreach ($like_creater_list as $creater)
                <li>
                    <a href="{{ route('creater.show', ['creater_id' => $creater->id]) }}">{{ $creater->name }}</a>
                </li>
            @endforeach
        </ul>
    </article>
@endsection