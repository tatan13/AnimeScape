@extends('layout')

@section('title')
    <title>AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>トップページ</h2>
    @auth
        <h3>あなたへのおすすめアニメ</h3>
        <table id="recommend_animes_table">
            <tbody>
                <tr>
                    <th>順位</th>
                    <th>アニメ名</th>
                    <th>会社名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>データ数</th>
                </tr>
                @foreach ($recommend_anime_list as $anime)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                        </td>
                        <td>{{ $anime->company }}</td>
                        <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                        <td>{{ $anime->median }}</td>
                        <td>{{ $anime->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endauth
    <h3>2022年冬クールアニメランキング</h3>
    <table id="animes_table">
        <tbody>
            <tr>
                <th>順位</th>
                <th>アニメ名</th>
                <th>会社名</th>
                <th>中央値</th>
                <th>データ数</th>
            </tr>
            @foreach ($animes as $anime)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                    </td>
                    <td>{{ $anime->company }}</td>
                    <td>{{ $anime->median }}</td>
                    <td>{{ $anime->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
@endsection
