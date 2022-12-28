@extends('layout')

@section('title')
    <title>アニメリスト AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('anime_list') }}
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
    <article class="anime_list">
        <h1>アニメリスト</h1>
        <div class="table-responsive">
            <section class="anime_list">
                <table class="anime_list_table">
                    <tbody>
                        <tr>
                            <th>アニメID</th>
                            <th>アニメ名</th>
                        </tr>
                        @foreach ($anime_all as $anime)
                            <tr>
                                <td>{{ $anime->id }}</td>
                                <td><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
        </section>
    </article>
@endsection
