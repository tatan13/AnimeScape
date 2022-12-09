@extends('layout')

@section('title')
    <title>ユーザー登録 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="register">
        <h1>ユーザー登録</h1>
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
        <p class="mt-2">
            <a href="{{ route('provider.redirect', ['provider' => 'twitter']) }}"><img src="twitter_icon.png" alt="Twitterログイン"></a>
        </p>
        <h2>注意事項</h2>
        <ul class="list-inline">
            <li>パスワードは8文字以上にしてください。</li>
            <li>Twitterログインから未登録の方もユーザー登録が可能です。Twitterのユーザー名が名前として登録されますが後で変更可能です。</li>
            <li>既に登録済みの方はマイページの"個人情報設定"から連携することで次回からTwitterログインが可能となります。</li>
        </ul>
    </article>
@endsection
