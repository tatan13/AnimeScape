<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('title')
    @if (env('APP_ENV') == 'production')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-227732808-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'UA-227732808-1');
        </script>
    @endif
</head>

<body>
    <header>
        <h1><a href="{{ route('index.show') }}">AnimeScape</a></h1>
    </header>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-2">
                <h2>メニュー</h2>
                <section class="login_menu">
                    <h3>ログインメニュー</h3>
                    @if (Auth::check())
                        ログイン中 : {{ Auth::user()->name }}
                        <ul>
                            <li><a
                                    href="{{ route('user.show', ['user_name' => auth()->user()->name]) }}">マイページ</a><br>
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
                                    <ul class="list-inline">
                                        <li>
                                            <select name="year" class="coor_year">
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                                <option value="2020">2020</option>
                                                <option value="2019">2019</option>
                                                <option value="2018">2018</option>
                                                <option value="2017">2017</option>
                                                <option value="2016">2016</option>
                                                <option value="2015">2015</option>
                                                <option value="2014">2014</option>
                                            </select>
                                            年
                                            <select name="coor" class="coor">
                                                <option value="1">冬</option>
                                                <option value="2">春</option>
                                                <option value="3">夏</option>
                                                <option value="4">秋</option>
                                            </select>
                                            <input type="submit" value="決定" />
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
                </section>
                <section class="search_menu">
                    <h3>検索</h3>
                    <form action="{{ route('search.show') }}" method="get">
                        @csrf
                        <select name="category">
                            <option value="anime" class="search_category_anime">アニメ</option>
                            <option value="cast" class="search_category_cast">声優</option>
                            <option value="user" class="search_category_user">ユーザー</option>
                        </select>
                        <input type="text" name="search_word" style="width: 90%;" class="search_word" value=""
                            size="15" /><br>
                        <input type="submit" value="検索" />
                    </form>
                </section>
                <section class="ranking_menu">
                    <h3>ランキング</h3>
                    <ul>
                        <li>
                            <form action="{{ route('anime_statistics.show') }}" name="all_statistics" method="get">
                                @csrf
                                <input type="hidden" name="category" value="median">
                                <a href="javascript:all_statistics.submit()">すべて</a>
                            </form>
                        </li>
                        <form action="{{ route('anime_statistics.show') }}" name="year_statistics" method="get">
                            @csrf
                            <input type="hidden" name="category" value="median">
                            <li><a href="javascript:year_statistics.submit()">年度ごと</a>

                                <ul class="list-inline">
                                    <li>
                                        <select name="year" class="year">
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                            <option value="2015">2015</option>
                                            <option value="2014">2014</option>
                                        </select>
                                        <input type="submit" value="決定" />
                                    </li>
                                </ul>
                            </li>
                        </form>
                        <form action="{{ route('anime_statistics.show', ['category' => 'median']) }}"
                            name="coor_statistics" method="get">
                            @csrf
                            <input type="hidden" name="category" value="median">
                            <li><a href="javascript:coor_statistics.submit()">クールごと</a>
                                <ul class="list-inline">
                                    <li>
                                        <select name="year" class="coor_year">
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                            <option value="2015">2015</option>
                                            <option value="2014">2014</option>
                                        </select>
                                        年
                                        <select name="coor" class="coor">
                                            <option value="1">冬</option>
                                            <option value="2">春</option>
                                            <option value="3">夏</option>
                                            <option value="4">秋</option>
                                        </select>
                                        <input type="submit" value="決定" />
                                    </li>
                                </ul>
                            </li>
                        </form>
                    </ul>
                    <section>
                        <h3>その他</h3>
                        <ul>
                            <li><a href="{{ route('contact.show') }}">要望フォーム</a></li>
                            <li><a href="{{ route('update_log.show') }}">更新履歴</a></li>
                        </ul>
                    </section>
                </section>
            </aside>
            <main class="col-md-10">
                @yield('main')
            </main>
        </div>
    </div>
    <footer>
        <hr>
        <a href="{{ route('privacy_policy.show') }}">プライバシーポリシー</a>
        @ 2022 animescape.link
    </footer>
    @yield('vue.js')
</body>

</html>
