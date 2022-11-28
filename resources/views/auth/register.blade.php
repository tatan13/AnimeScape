@extends('layout')

@section('title')
    <title>ユーザー登録ページ AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="register">
        <h1>ユーザー登録ページ</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('register') }}" class="register_form" method="POST">
            @csrf
            <label for="name">ログインID</label><br>
            <input type="text" size="15" name="name" class="name" value="{{ old('name') }}" required><br>
            <label for="password">パスワード</label><br>
            <input type="password" size="15" name="password" class="password" required><br>
            <label for="password_confirmation">パスワード再入力</label><br>
            <input type="password" size="15" name="password_confirmation" class="password-confirm" required><br>
            <input type="submit" value="新規登録">
        </form>
        <h2>注意事項</h2>
        パスワードは8文字以上にしてください。
    </article>
@endsection
