@extends('layout')

@section('title')
    <title>クリエイターリスト AnimeScape -アニメ批評空間-</title>
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
    <article class=creater_list>
        <h1>クリエイターリスト</h1>
        <section class="anime_creater_list">
            <div class="table-responsive">
                <table class="creater_list_table">
                    <tbody>
                        <tr>
                            <th>クリエイターID</th>
                            <th>クリエイター名</th>
                        </tr>
                        @foreach ($creater_all as $creater)
                            <tr>
                                <td>{{ $creater->id }}</td>
                                <td><a
                                        href="{{ route('creater.show', ['creater_id' => $creater->id]) }}">{{ $creater->name }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </article>
@endsection
