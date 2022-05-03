@extends('layout')

@section('title')
    <title>パスワード再発行申請画面 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="forget_password">
        <h2>パスワード再発行申請画面</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <label for="email">ログインID</label><br>
            <input type="text" name="email" value="{{ old('email') }}" required><br>
            <input type="submit" value="送信">
        </form>
        <h3>注意事項</h3>
        個人情報設定で登録したメールアドレスを入力してください。
    </div>
    </div>
    </div>
@endsection
