@extends('layout')

@section('title')
    <title>{{ $cast->name }}の削除申請画面 AnimeScape</title>
@endsection

@section('main')
    <article class="delete_cast_request">
        <h2>{{ $cast->name }}の削除申請画面</h2>
        <h3><a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a></h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('delete_cast_request.post', ['cast_id' => $cast->id]) }}" class="delete_cast_request_form" method="POST">
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