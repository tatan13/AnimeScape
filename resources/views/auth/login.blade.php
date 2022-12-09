@extends('layout')

@section('title')
    <title>ログイン AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="login">
        <h1>ログイン</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        @if (session('oauth_error'))
            <div class="alert alert-danger">
                {{ session('oauth_error') }}
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
        ユーザー登録はこちらへ → <a href="{{ route('register') }}">ユーザー登録</a><br>
        パスワードを再設定はこちらへ → <a href="{{ route('password.request') }}">パスワード再設定申請</a>
        <p class="mt-2">
            <a href="{{ route('provider.redirect', ['provider' => 'twitter']) }}"><img src="twitter_icon.png" alt="Twitterログイン"></a>
        </p>
        <h2>注意事項</h2>
        <ul class="list-inline">
            <li>Twitterログインから未登録の方もユーザー登録が可能です。Twitterのユーザー名が名前として登録されますが後で変更可能です。</li>
            <li>既に登録済みの方はマイページの"個人情報設定"から連携することで次回からTwitterログインが可能となります。</li>
        </ul>
    </article>
@endsection
