@extends('layout')

@section('title')
    <title>パスワード再発行 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="reset_password">
        <h2>パスワード再発行</h2>
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
        <form action="{{ route('password.update') }}" class="reset_password_form" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" required>
            <label for="email">メールアドレス</label><br>
            <input type="email" name="email" class="email" value="{{ old('email') }}" required><br>
            <label for="password">パスワード</label><br>
            <input type="password" size="15" name="password" class="password" required><br>
            <label for="password_confirmation">パスワード再入力</label><br>
            <input type="password" size="15" name="password_confirmation" class="password_confirmation" required><br>
            <input type="submit" value="送信">
        </form>
        <h3>注意事項</h3>
        パスワードは8文字以上にしてください。
    </article>
@endsection
