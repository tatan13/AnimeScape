@extends('layout')

@section('title')
    <title>タグリスト AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('tag_list') }}
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
    <article class="tag_list">
        <h1>タグリスト</h1>
        <section class="anime_tag_list">
            <div class="table-responsive">
                <table class="tag_list_table">
                    <tbody>
                        <tr>
                            <th>タグID</th>
                            <th>タグ名</th>
                            <th>タググループ名</th>
                        </tr>
                        @foreach ($tag_all as $tag)
                            <tr>
                                <td>{{ $tag->id }}</td>
                                <td><a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>
                                </td>
                                <td>{{ $tag->tag_group_id_label }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </article>
@endsection
