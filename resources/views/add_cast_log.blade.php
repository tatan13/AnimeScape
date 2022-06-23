@extends('layout')

@section('title')
    <title>声優の追加履歴 AnimeScape</title>
@endsection

@section('main')
    <article class="add_cast_log">
        <h2>声優の追加履歴</h2>
        @if (session('flash_add_cast_log_message'))
            <div class="alert alert-success">
                {{ session('flash_add_cast_log_message') }}
            </div>
        @endif
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
    </article>
@endsection