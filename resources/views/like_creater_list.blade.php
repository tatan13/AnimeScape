@extends('layout')

@section('title')
    <title>{{ $user->name }}さんのお気に入りクリエイター AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="like_creater_list">
        <h2>{{ $user->name }}さんのお気に入りクリエイター</h2>
        <div class="title">{{ $user->name }}</div>
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
