@extends('layout')

@section('title')
    <title>作品の追加履歴 AnimeScape -アニメ批評空間-</title>
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
    <article class="add_anime_log">
        <h1>アニメの追加履歴</h1>
        @if (session('flash_add_anime_log_message'))
            <div class="alert alert-success">
                {{ session('flash_add_anime_log_message') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="add_anime_log_table">
                <tbody>
                    <tr>
                        <th>アニメ名</th>
                        <th>追加日</th>
                    </tr>
                    @foreach ($add_anime_list as $add_anime)
                        <tr>
                            <td><a
                                    href="{{ route('anime.show', ['anime_id' => $add_anime->anime_id]) }}">{{ $add_anime->title }}</a>
                            </td>
                            <td>{{ $add_anime->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
