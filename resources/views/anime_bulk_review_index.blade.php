@extends('layout')

@section('title')
    <title>データ一括入力メニュー AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="anime_bulk_review_index">
        <h1>データ一括入力メニュー</h1>
        <section class="anime_bulk_review_menu">
            <h2>視聴完了後メニュー</h2>
            <ul>
                <li>
                    <form action="{{ route('coor_anime_bulk_review.show') }}" name="coor_anime_bulk_review" method="get">
                        @csrf
                        <a href="javascript:coor_anime_bulk_review.submit()">クール毎</a>
                        <select name="year" class="year">
                            @include('layout/select_year')
                        </select>
                        年
                        <select name="coor" class="coor">
                            <option value="1" {{ $coor ?? 1 == 1 ? 'selected' : '' }}>冬</option>
                            <option value="2" {{ $coor ?? 1 == 2 ? 'selected' : '' }}>春</option>
                            <option value="3" {{ $coor ?? 1 == 3 ? 'selected' : '' }}>夏</option>
                            <option value="4" {{ $coor ?? 1 == 4 ? 'selected' : '' }}>秋</option>
                        </select>クールで
                        <input type="submit" value="絞りこむ">
                    </form>
                </li>
                <li>
                    <a href="{{ route('now_watch_anime_bulk_review.show') }}">視聴中</a>
                </li>
                <li>
                    <a href="{{ route('score_anime_bulk_review.show') }}">得点入力済み</a>
                </li>
            </ul>
        </section>
        <section class="anime_bulk_before_review_menu">
            <h2>視聴完了前メニュー</h2>
            <ul>
                <li>
                    <form action="{{ route('coor_anime_bulk_before_review.show') }}" name="coor_anime_bulk_before_review" method="get">
                        @csrf
                        <a href="javascript:coor_anime_bulk_before_review.submit()">クール毎</a>
                        <select name="year" class="year">
                            @include('layout/select_year')
                        </select>
                        年
                        <select name="coor" class="coor">
                            <option value="1" {{ $coor ?? 1 == 1 ? 'selected' : '' }}>冬</option>
                            <option value="2" {{ $coor ?? 1 == 2 ? 'selected' : '' }}>春</option>
                            <option value="3" {{ $coor ?? 1 == 3 ? 'selected' : '' }}>夏</option>
                            <option value="4" {{ $coor ?? 1 == 4 ? 'selected' : '' }}>秋</option>
                        </select>クールで
                        <input type="submit" value="絞りこむ">
                    </form>
                </li>
                <li>
                    <a href="{{ route('now_watch_anime_bulk_before_review.show') }}">視聴中</a>
                </li>
                <li>
                    <a href="{{ route('before_score_anime_bulk_before_review.show') }}">得点入力済み</a>
                </li>
            </ul>
        </section>
    </article>
@endsection
