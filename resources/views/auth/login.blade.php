@extends('layout')

@section('title')
    <title>ログインページ AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="login">
        <h2>ログインページ</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('login') }}" class="login_form" method="POST">
            @csrf
            <label for="name">ログインID</label><br>
            <input type="text" size="15" name="name" class="name" required><br>
            <label for="password">パスワード</label><br>
            <input type="password" size="15" name="password" class="password" required><br>
            <input type="checkbox" name="remember-me" class="remember-me"> ログイン状態を記憶する<br>
            <input type="submit" value="ログイン">
        </form>
        IDを取得していない方はこちらへ → <a href="{{ route('register') }}">新規ID取得</a><br>
        パスワードを忘れた方はこちらへ → <a href="{{ route('password.request') }}">パスワード再設定申請</a>
    </article>
@endsection
