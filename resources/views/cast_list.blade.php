@extends('layout')

@section('title')
    <title>声優リスト AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection


@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@if (env('APP_ENV') == 'production')
    @section('main_adsense_smartphone')
        @include('layout.horizontal_adsense_smartphone')
    @endsection
@endif

@section('main')
    <article class="cast_list">
        <h1>声優リスト</h1>
        <div class="table-responsive">
            <section class="cast_act_anime_list">
                <table class="cast_list_table">
                    <tbody>
                        <tr>
                            <th>声優ID</th>
                            <th>声優名</th>
                        </tr>
                        @foreach ($cast_all as $cast)
                            <tr>
                                <td>{{ $cast->id }}</td>
                                <td><a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
        </section>
    </article>
@endsection
