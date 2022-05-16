@extends('layout')

@section('title')
    <title>{{ $anime->title }}の削除申請画面 AnimeScape</title>
@endsection

@section('main')
    <article class="delete_anime_request">
        <h2>{{ $anime->title }}の削除申請画面</h2>
        <h3><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('delete_anime_request.post', ['anime_id' => $anime->id]) }}" class="delete_anime_request_form" method="POST">
            @csrf
            <label for="remark">削除事由</label><br>
            <input type="text" size="100" name="remark" class="remark"
                value=""><br>
            <input type="submit" value="送信"><br>
        </form>
        <h3>注意事項</h3>
        削除事由は400文字以内で入力してください。
    </article>
@endsection