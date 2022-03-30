@extends('layout')

@section('title')
    <title>認証画面 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="login">
        <h2>認証画面</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="name">ログインID</label><br>
            <input type="text" size="15" name="uid" required><br>
            <label for="password">パスワード</label><br>
            <input type="password" size="15" name="password" required><br>
            <input type="submit" value="ログイン">
        </form>
        IDを取得していない方はこちらへ → <a href="{{ route('register') }}">新規ID取得</a>
    </div>
    </div>
    </div>
@endsection
