@extends('layout')

@section('title')
    <title>アニメランキング（中央値順）AnimeScape</title>
@endsection

@section('main')
    <article class="anime_statistics">
        <h2>{{ !is_null($year) ? $year . '年' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }}アニメランキング（{{ App\Models\Anime::getCategoryLabel($category) }}順）
        </h2>
        <h3>検索条件変更</h3>
        <form action="{{ route('anime_statistics.show') }}" class="search_parameters_form" method="GET">
            @csrf
            <input type="hidden" name="year" class="year" value="{{ $year }}">
            <input type="hidden" name="coor" class="coor" value="{{ $coor }}">
            データ数が
            <input type="number" name="count" class="count" value="{{ $count ?? 0 }}" style="width:60px;">
            以上のアニメで
            <select name="category" class="category">
                <option value="median" {{ $category == 'median' ? 'selected' : '' }}>中央値</option>
                <option value="average" {{ $category == 'average' ? 'selected' : '' }}>平均値</option>
                <option value="count" {{ $category == 'count' ? 'selected' : '' }}>データ数</option>
            </select>
            順に<input type="submit" value="絞り込む">
        </form>
        <h3>ランキング</h3>
        <form action="{{ route('anime_statistics.show') }}" name="previous" class="d-inline" method="get">
            @csrf
            <input type="hidden" name="category" class="category" value="{{ $category ?? 'median' }}">
            <input type="hidden" name="count" class="count" value="{{ $count ?? 0 }}">
            <input type="hidden" name="year" class="year" value="{{ $coor == 1 ? $year - 1 : $year }}">
            <input type="hidden" name="coor" class="coor" value="{{ $coor == 1 ? 4 : $coor - 1 }}">
            <a href="javascript:previous.submit()">前クールへ</a>
        </form>
        <form action="{{ route('anime_statistics.show') }}" name="next" class="d-inline" method="get">
            @csrf
            <input type="hidden" name="category" class="category" value="{{ $category ?? 'median' }}">
            <input type="hidden" name="count" class="count" value="{{ $count ?? 0 }}">
            <input type="hidden" name="year" class="year" value="{{ $coor == 4 ? $year + 1 : $year }}">
            <input type="hidden" name="coor" class="coor" value="{{ $coor == 4 ? 1 : $coor + 1 }}">
            <a href="javascript:next.submit()">次クールへ</a>
        </form>
        <table class="anime_ranking_table">
            <tbody>
                <tr>
                    <th>順位</th>
                    <th>アニメ名</th>
                    <th>会社名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>平均値</th>
                    <th>データ数</th>
                    @auth
                        <th>つけた得点</th>
                    @endauth
                </tr>
                @foreach ($animes as $anime)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                        </td>
                        <td>{{ $anime->company }}</td>
                        <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                        <td>{{ $anime->median }}</td>
                        <td>{{ $anime->average }}</td>
                        <td>{{ $anime->count }}</td>
                        @auth
                            <td>{{ $anime->userReview->score ?? '' }}</td>
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection
