@extends('layout')

@section('title')
    <title>アニメランキング（中央値順）AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>{{ !is_null($year) ? $year.'年' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor).'クール' : ''  }}アニメランキング（{{ App\Models\Anime::getCategoryLabel($category) }}順）</h2>
    <h3>検索条件変更</h3>
    <form action="{{ route('anime_statistics.show') }}">
        @csrf
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="coor" value="{{ $coor }}">
        データ数が
        <input type="number" name="count" value="{{ $count ?? 0 }}" style="width:60px;">
        以上のアニメで
        <select name="category">
            <option value="median" {{ $category == 'median' ? 'selected' : '' }} >中央値</option>
            <option value="average" {{ $category == 'average' ? 'selected'  : '' }} >平均値</option>
            <option value="count" {{ $category == 'count' ? 'selected'  : '' }} >データ数</option>
        </select>
        順に<input type="submit" value="絞り込む">
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
                        <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a></td>
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
