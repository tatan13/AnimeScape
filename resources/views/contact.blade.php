@extends('layout')

@section('title')
    <title>要望フォーム AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="request_form">
        <h2>要望フォーム</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        名前を書かないと名無しさんになります。
        <form action="{{ route('contact.post') }}" method="POST">
            @csrf
            <label for="name">名前</label><br>
            <input type="text" size="15" name="name"><br>
            <label for="comment">内容</label><br>
            <textarea name="comment" cols="80" rows="5"></textarea><br>
            <label for="auth">認証</label><br>
            <input type="text" size="15" name="auth">「にんしょう」と入力してください。SPAM対策です。<br>
            <input type="submit" value="書き込む">
        </form>
    </div>
    <hr>
    @foreach ($contacts as $contact)
        <strong>{{ $contact->name }}</strong><br>
        {{ $contact->created_at }}<br>
        {{ $contact->comment }}
        <hr>
    @endforeach
    </div>
    </div>
@endsection
