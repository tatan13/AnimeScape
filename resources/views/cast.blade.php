@extends('layout')

@section('title')
    <title>{{ $cast->name }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="app">
        <h2>
            <a href="{{ route('cast', ['id' => $cast->id]) }}">{{ $cast->name }}</a>
        </h2>
        <span><strong>{{ $cast->name }}</strong></span><br>
        @auth
            <like-cast-component :props-cast-id="{{ json_encode($cast->id) }}"
                :default-is-like-cast="{{ json_encode(Auth::user()->isLikeCast($cast->id)) }}">
                ></like-cast-component>
        @endauth
        <h3>声優（計{{ $act_animes->count() }}本）</h3>
        <table>
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>会社名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>データ数</th>
                </tr>
                @foreach ($act_animes as $act_anime)
                    <tr>
                        <td><a href="{{ route('anime', ['id' => $act_anime->id]) }}">{{ $act_anime->title }}</a>
                        </td>
                        <td>{{ $act_anime->company }}</td>
                        <td>
                            {{ $act_anime->year }}年{{ $act_anime->coor_label }}クール
                        </td>
                        <td>{{ $act_anime->median }}</td>
                        <td>{{ $act_anime->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection
@section('vue.js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
