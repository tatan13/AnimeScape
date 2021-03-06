@extends('layout')

@section('title')
    <title>声優リスト AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class=cast_list>
        <h2>声優リスト</h2>
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
        </section>
    </article>
@endsection
