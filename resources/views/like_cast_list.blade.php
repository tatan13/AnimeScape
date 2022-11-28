@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入り声優 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="like_cast_list">
        <h1>{{ $user->name }}さんのお気に入り声優</h1>
        <div class="title">{{ $user->name }}</div>
        <h2>お気に入り声優</h2>
        <ul>
            @foreach ($like_cast_list as $cast)
                <li>
                    <a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a>
                </li>
            @endforeach
        </ul>
    </article>
@endsection
