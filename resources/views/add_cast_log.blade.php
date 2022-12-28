@extends('layout')

@section('title')
    <title>声優の追加履歴 AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('add_cast_log') }}
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
    <article class="add_cast_log">
        <h1>声優の追加履歴</h1>
        @if (session('flash_add_cast_log_message'))
            <div class="alert alert-success">
                {{ session('flash_add_cast_log_message') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="add_cast_log_table">
                <tbody>
                    <tr>
                        <th>声優名</th>
                        <th>追加日</th>
                    </tr>
                    @foreach ($add_cast_list as $add_cast)
                        <tr>
                            <td><a
                                    href="{{ route('cast.show', ['cast_id' => $add_cast->cast_id]) }}">{{ $add_cast->name }}</a>
                            </td>
                            <td>{{ $add_cast->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
