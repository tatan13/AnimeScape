@extends('layout')

@section('title')
    <title>パスワード再発行画面 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="reset_password">
        <h2>パスワード再発行画面</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <label for="email">ログインID</label><br>
            <input type="text" name="email" value="{{ old('email') }}" required><br>
            <label for="password">パスワード</label><br>
            <input type="password" size="15" name="password" required><br>
            <label for="password_confirmation">パスワード再入力</label><br>
            <input type="password" size="15" name="password_confirmation" required><br>
            <input type="submit" value="送信">
        </form>
        <h3>注意事項</h3>
        パスワードは8文字以上にしてください。
    </div>
    </div>
    </div>
@endsection
