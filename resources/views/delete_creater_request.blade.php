@extends('layout')

@section('title')
    <title>{{ $creater->name }}の削除申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="delete_creater_request">
        <h1>{{ $creater->name }}の削除申請</h1>
        <h2><a href="{{ route('creater.show', ['creater_id' => $creater->id]) }}">{{ $creater->name }}</a></h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('delete_creater_request.post', ['creater_id' => $creater->id]) }}" class="delete_creater_request_form" method="POST">
            @csrf
            <label for="remark">削除事由</label><br>
            <input type="text" size="100" name="remark" class="remark" style="width: 100%;"
                value=""><br>
            <input type="submit" value="送信"><br>
        </form>
        <h2>注意事項</h2>
        削除事由は400文字以内で入力してください。
    </article>
@endsection