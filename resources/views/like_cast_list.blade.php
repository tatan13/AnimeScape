@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入り声優 AnimeScape</title>
@endsection

@section('main')
    <article class="like_cast_list">
        <h2>{{ $user->name }}さんのお気に入り声優</h2>
        <strong>{{ $user->name }}</strong>
        <h3>お気に入り声優</h3>
        <ul>
            @foreach ($like_cast_list as $cast)
                <li>
                    <a href="{{ route('cast.show', ['id' => $cast->id]) }}">{{ $cast->name }}</a>
                </li>
            @endforeach
        </ul>
    </article>
@endsection
