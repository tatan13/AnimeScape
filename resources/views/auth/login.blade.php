<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/app.js"></script>
    <title>認証画面 AnimeScape -アニメ批評空間-</title>
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
        </div>
    </main>
</body>

</html>
