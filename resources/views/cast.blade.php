@extends('layout')

@section('title')
    <title>{{ $cast->name }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>
                <a href="{{ route('cast', ['id' => $cast->id]) }}">{{ $cast->name }}</a>
            </h2>
            <span><strong>{{ $cast->name }}</strong></span><br>
            @auth
                @if(!Auth::user()->like_casts->contains('cast_id', $cast->id))
                    <a href="{{ route('cast.like', ['id' => $cast->id]) }}">お気に入りの声優として登録する</a>
                @else
                    <a href="{{ route('cast.dislike', ['id' => $cast->id]) }}">お気に入りを解除する</a>
                @endif
            @endauth
            <h3>声優（計{{ count($occupations) }}本）</h3>
            <table>
                <tbody>
                    <tr>
                        <th>アニメ名</th>
                        <th>会社名</th>
                        <th>放送クール</th>
                        <th>中央値</th>
                        <th>データ数</th>
                    </tr>
                    @foreach ($occupations as $occupation)
                        <tr>
                            <td><a href="{{ route('anime', ['id' => $occupation->anime->id]) }}">{{ $occupation->anime->title }}</a></td>
                            <td>{{ $occupation->anime->company }}</td>
                            <td>
                                {{ $occupation->anime->year }}年{{ $occupation->anime->coor_label }}クール
                            </td>
                            <td>{{ $occupation->anime->median }}</td>
                            <td>{{ $occupation->anime->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
