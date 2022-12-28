@extends('layout')

@section('title')
    <title>クリエイターの追加履歴 AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('add_creater_log') }}
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
    <article class="add_creater_log">
        <h1>クリエイターの追加履歴</h1>
        @if (session('flash_add_creater_log_message'))
            <div class="alert alert-success">
                {{ session('flash_add_creater_log_message') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="add_creater_log_table">
                <tbody>
                    <tr>
                        <th>クリエイター名</th>
                        <th>追加日</th>
                    </tr>
                    @foreach ($add_creater_list as $add_creater)
                        <tr>
                            <td><a
                                    href="{{ route('creater.show', ['creater_id' => $add_creater->creater_id]) }}">{{ $add_creater->name }}</a>
                            </td>
                            <td>{{ $add_creater->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
