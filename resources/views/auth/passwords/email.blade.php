@extends('layout')

@section('title')
    <title>パスワード再発行申請 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="forget_password">
        <h1>パスワード再発行申請</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('password.email') }}" class="forget_password" method="POST">
            @csrf
            <label for="email">メールアドレス</label><br>
            <input type="email" name="email" class="email" value="{{ old('email') }}" required><br>
            <input type="submit" value="送信">
        </form>
        <h2>注意事項</h2>
        個人情報設定で登録したメールアドレスを入力してください。
    </article>
@endsection
