<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    @yield('title')
</head>

<body>
    <header>
        <h1><a href="{{ route('index') }}">AnimeScape -アニメ批評空間-</a></h1><br>
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div id="login">
                    <h2>ログイン</h2>
                    @if (Auth::check())
                    <p>ログイン中です。</p>
                    <h3>ログインメニュー</h3>
                    <ul>
                        <li><a href="{{ route('user', ['uid' => auth()->user()->uid]) }}">マイページ</a><br></li>
                        <li>
                            <form action="{{ route('logout') }}" name="logout" method="POST">
                                @csrf
                                    <a href="javascript:logout.submit()">ログアウト</a>
                                </form>
                            </li>
                        </ul>
                        @else
                        <ul>
                            <li><a href="{{ route('login') }}">ログイン</a><br></li>
                            <li><a href="{{ route('register') }}" target="_self">新規ID作成</a><br></li>
                        </ul>
                        @endif
                    </div>
                    <div id="search_menu">
                        <h2>検索</h2>
                    <form action="{{ route('search') }}" method="get">
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
            </div>
            @yield('main')
            <div class="col-md-2">
                <div id="menulist">
                    <h2>メニュー</h2>
                    <h3>ランキング</h3>
                    <ul>
                        <li>
                            <form action="{{ route('all_statistics', ['category' => 1]) }}" name="all_statistics"
                                method="get">
                                @csrf
                                <a href="javascript:all_statistics.submit()">すべて</a>
                            </form>
                        </li>
                        <form action="{{ route('year_statistics', ['category' => 1]) }}" name="year_statistics"
                            method="get">
                            @csrf
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
                        
                        <form action="{{ route('coor_statistics', ['category' => 1]) }}" name="coor_statistics"
                            method="get">
                            @csrf
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
                        <li><a href="{{ route('contact.index') }}">要望フォーム</a></li>
                    </ul>
                </div>
            </div>
        </main>
            <script src="{{ asset('js/app.js') }}" defer></script>
        </body>
        </html>
        