@extends('layout')

@section('title')
    <title>AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>お知らせ</h2>
            <h3>2022年冬クールアニメランキング</h3>
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <div id="animes_table">
                            <table>
                                <tbody>
                                    <tr>
                                        <th>アニメ名</th>
                                        <th>会社名</th>
                                        <th>中央値</th>
                                        <th>データ数</th>
                                    </tr>
                                    @foreach ($animes as $anime)
                                        <tr>
                                            <td><a href="{{ route('anime', ['id' => $anime->id]) }}">{{ $anime->title }}</a></td>
                                            <td>{{ $anime->company }}</td>
                                            <td>{{ $anime->median }}</td>
                                            <td>{{ $anime->count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
