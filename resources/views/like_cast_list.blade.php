@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入り声優 AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="like_cast_list">
        <h2>{{ $user->name }}さんのお気に入り声優</h2>
        <strong>{{ $user->name }}</strong>
        <h3>お気に入り声優</h3>
        <ul>
            @foreach ($like_cast_list as $cast)
                <li>
                    <a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a>
                </li>
            @endforeach
        </ul>
    </article>
@endsection
