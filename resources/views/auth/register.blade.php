<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/app.js"></script>
    <title>ID発行画面 AnimeScape -アニメ批評空間-</title>
</head>

<body>
    <header>
        <h1><a href="{{ route('index') }}">AnimeScape -アニメ批評空間-</a></h1><br>
    </header>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div id="register">
                        <h2>ID発行画面</h2>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $message)
                                    <p>{{ $message }}</p>
                                @endforeach
                            </div>
                        @endif
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <label for="name">ログインID</label><br>
                            <input type="text" size="15" name="uid" id="uid" value="{{ old('uid') }}" required><br>
                            <label for="password">パスワード</label><br>
                            <input type="password" size="15" name="password" id="password" required><br>
                            <label for="password_confirmation">パスワード再入力</label><br>
                            <input type="password" size="15" name="password_confirmation" id="password-confirm"
                                required><br>
                            <input type="submit" value="新規登録">
                        </form>
                        <h3>注意事項</h3>
                        パスワードは8文字以上にしてください。
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
