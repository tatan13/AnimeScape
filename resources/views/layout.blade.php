<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    @yield('title')
</head>

<body>
    <header>
        <h1><a href="{{ route('index.show') }}">AnimeScape -アニメ批評空間-</a></h1><br>
    </header>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <h2>メニュー</h2>
                    <div id="login">
                        <h3>ログインメニュー</h3>
                        @if (Auth::check())
                            ログイン中 : {{ Auth::user()->name }}
                            <ul>
                                <li><a href="{{ route('user.show', ['user_name' => auth()->user()->name]) }}">マイページ</a><br>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" name="logout" method="POST">
                                        @csrf
                                        <a href="javascript:logout.submit()">ログアウト</a>
                                    </form>
                                </li>
                                <form action="{{ route('anime_review_list.show') }}" name="anime_review_list"
                                    method="get">
                                    @csrf
                                    <li>
                                        <a href="javascript:anime_review_list.submit()">得点一括入力</a>
                                        <ul>
                                            <li>
                                                <select name="year" id="coor_year">
                                                    <option value="2022">2022</option>
                                                    <option value="2021">2021</option>
                                                </select>
                                                年
                                                <select name="coor" id="coor">
                                                    <option value="1">冬</option>
                                                    <option value="2">春</option>
                                                    <option value="3">夏</option>
                                                    <option value="4">秋</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </li>
                                </form>
                            </ul>
                        @else
                            <ul>
                                <li><a href="{{ route('login') }}">ログイン</a><br></li>
                                <li><a href="{{ route('register') }}" target="_self">新規ID作成</a><br></li>
                            </ul>
                        @endif
                    </div>
                    <div id="search_menu">
                        <h3>検索</h3>
                        <form action="{{ route('search.show') }}" method="get">
                            @csrf
                            <select name="category">
                                <option value="anime" id="search_category_anime">アニメ</option>
                                <option value="cast" id="search_category_cast">声優</option>
                                <option value="user" id="search_category_user">ユーザー</option>
                            </select>
                            <input type="text" name="search_word" style="width: 90%;" id="search_word" value=""
                                size="15" /><br>
                            <input type="submit" value="検索" />
                        </form>
                    </div>
                    <div id="menulist">
                        <h3>ランキング</h3>
                        <ul>
                            <li>
                                <form action="{{ route('anime_statistics.show') }}" name="all_statistics"
                                    method="get">
                                    @csrf
                                    <input type="hidden" name="category" value="median">
                                    <a href="javascript:all_statistics.submit()">すべて</a>
                                </form>
                            </li>
                            <form action="{{ route('anime_statistics.show') }}" name="year_statistics" method="get">
                                @csrf
                                <input type="hidden" name="category" value="median">
                                <li><a href="javascript:year_statistics.submit()">年度ごと</a>
                                    <ul>
                                        <li>
                                            <select name="year" id="year">
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                            </select>
                                        </li>
                                    </ul>
                                </li>
                            </form>
                            <form action="{{ route('anime_statistics.show', ['category' => 'median']) }}"
                                name="coor_statistics" method="get">
                                @csrf
                                <input type="hidden" name="category" value="median">
                                <li><a href="javascript:coor_statistics.submit()">クールごと</a>
                                    <ul>
                                        <li>
                                            <select name="year" id="coor_year">
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                            </select>
                                            年
                                            <select name="coor" id="coor">
                                                <option value="1">冬</option>
                                                <option value="2">春</option>
                                                <option value="3">夏</option>
                                                <option value="4">秋</option>
                                            </select>
                                        </li>
                                    </ul>
                                </li>
                            </form>
                        </ul>
                        <h3>要望フォーム</h3>
                        <ul>
                            <li><a href="{{ route('contact.show') }}">要望フォーム</a></li>
                        </ul>
                        <h3>更新履歴</h3>
                        <ul>
                            <li><a href="{{ route('update_log.show') }}">更新履歴</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-10">
                    <div id="main">
                        @yield('main')
    </main>
    <footer>
        <hr>
        tatan13
    </footer>
    @yield('vue.js')
</body>

</html>
