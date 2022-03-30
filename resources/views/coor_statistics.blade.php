@extends('layout')

@section('title')
    <title>アニメランキング（中央値順）AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>{{ $year }}年{{ $coor->getLabel() }}アニメランキング（{{ $category->getLabel() }}順）
        <h3>検索条件変更</h3>
        <form action="{{ route('coor_statistics', ['category' => $category->getNum()]) }}">
            @csrf
            データ数が
            <select name="count">
                <option value="5" selected="selected">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
                <option value="500">500</option>
            </select>
            以上のアニメで
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="coor" value="{{ $coor->getNum() }}">
            <input type="submit" value="絞り込む">
        </form>
        <form action="{{ route('coor_statistics', ['category' => 1]) }}" name="coor_statistics_median">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="coor" value="{{ $coor->getNum() }}">
            <a href="javascript:coor_statistics_median.submit()">中央値順</a>
        </form>
        <form action="{{ route('coor_statistics', ['category' => 2]) }}" name="coor_statistics_average">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="coor" value="{{ $coor->getNum() }}">
            <a href="javascript:coor_statistics_average.submit()">平均値順</a>
        </form>
        <form action="{{ route('coor_statistics', ['category' => 3]) }}" name="coor_statistics_count">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="coor" value="{{ $coor->getNum() }}">
            <a href="javascript:coor_statistics_count.submit()">データ数順</a>
        </form>
        <h3>ランキング</h3>
        <div id=ranking_table>
            <table>
                <tbody>
                    <tr>
                        <th>アニメ名</th>
                        <th>会社名</th>
                        <th>中央値</th>
                        <th>平均値</th>
                        <th>データ数</th>
                    </tr>
                    @foreach ($animes as $anime)
                        <tr>
                            <td><a href="{{ route('anime', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                            </td>
                            <td>{{ $anime->company }}</td>
                            <td>{{ $anime->median }}</td>
                            <td>{{ $anime->average }}</td>
                            <td>{{ $anime->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
        </div>
    @endsection
