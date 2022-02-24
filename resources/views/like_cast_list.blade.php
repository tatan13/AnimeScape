@extends('layout')

@section('title')
    <title>{{ $user->uid }}さんのお気に入り声優 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>{{ $user->uid }}さんのお気に入り声優</h2>
            <strong>{{ $user->uid }}</strong>
            <h3>お気に入り声優</h3>
            <div id="like_casts">
                <ul>
                    @foreach ($like_casts as $cast)
                        <li>
                            <a href="{{ route('cast', ['id' => $cast->cast->id]) }}">{{ $cast->cast->name }}</a>
                        </li>
                    @endforeach
                <ul>
            </div>
        </div>
    </div>
@endsection