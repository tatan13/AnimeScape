<!DOCTYPE html>
<html lang="ja">

<head prefix="og: https://ogp.me/ns#">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta property="og:url" content="https://www.animescape.link/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="AnimeScape -アニメ批評空間-" />
    <meta property="og:description" content="アニメの情報やアニメに付けたユーザーの得点や感想を集めた統計サイトです。視聴管理、アニメ探し等様々な用途でご利用ください。" />
    <meta property="og:site_name" content="AnimeScape -アニメ批評空間-" />
    <meta property="og:image" content="https://www.animescape.link/animescape_ogp.png" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@tatan_tech" />
    <meta name="keywords" content="アニメ,アニメスケープ,アニスケ">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
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
        @yield('adsense')
    @endif
</head>

<body>
    <div class="container mt-4">
        <header>
            <div class="d-flex flex-wrap justify-content-between">
                <div class="mb-2 align-self-center">
                    <div class="animescape"><a href="{{ route('index.show') }}">AnimeScape -アニメ批評空間-</a></div>
                </div>
                @if (env('APP_ENV') == 'production')
                    @yield('title_adsense')
                @endif
            </div>
        </header>
        <div class="row">
            <aside class="col-xl-2 col-sm-3">
                <h1>メニュー</h1>
                <section class="login_menu">
                    <h2>ログインメニュー</h2>
                    @if (Auth::check())
                        <form action="{{ route('logout') }}" name="logout" method="POST">
                            @csrf
                            ログイン中 : {{ Auth::user()->name }}
                            <a href="javascript:logout.submit()">ログアウト</a>
                        </form>
                        <ul>
                            <li><a href="{{ route('user.show', ['user_id' => auth()->user()->id]) }}">マイページ</a><br>
                            </li>
                            <form action="{{ route('anime_review_list.show') }}" name="anime_review_list"
                                method="get">
                                @csrf
                                <li>
                                    <a href="javascript:anime_review_list.submit()">得点一括入力</a>
                                    <ul class="list-inline">
                                        <li>
                                            @include('layout.select_year')
                                            年
                                            <select name="coor" class="coor">
                                                <option value="1"
                                                    {{ is_null($coor ?? null) ? '' : ($coor == 1 ? 'selected' : '') }}>
                                                    冬
                                                </option>
                                                <option value="2"
                                                    {{ is_null($coor ?? null) ? '' : ($coor == 2 ? 'selected' : '') }}>
                                                    春
                                                </option>
                                                <option value="3"
                                                    {{ is_null($coor ?? null) ? '' : ($coor == 3 ? 'selected' : '') }}>
                                                    夏
                                                </option>
                                                <option value="4"
                                                    {{ is_null($coor ?? null) ? '' : ($coor == 4 ? 'selected' : '') }}>
                                                    秋
                                                </option>
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
                    <h2>検索メニュー</h2>
                    <form action="{{ route('search.show') }}" method="get">
                        @csrf
                        <select name="category">
                            <option value="anime"
                                {{ is_null($category ?? null) ? '' : ($category == 'anime' ? 'selected' : '') }}>アニメ
                            </option>
                            <option value="cast"
                                {{ is_null($category ?? null) ? '' : ($category == 'cast' ? 'selected' : '') }}>声優
                            </option>
                            <option value="creater"
                                {{ is_null($category ?? null) ? '' : ($category == 'creater' ? 'selected' : '') }}>
                                クリエイター
                            </option>
                            <option value="company"
                                {{ is_null($category ?? null) ? '' : ($category == 'company' ? 'selected' : '') }}>
                                制作会社
                            </option>
                            <option value="user"
                                {{ is_null($category ?? null) ? '' : ($category == 'user' ? 'selected' : '') }}>ユーザー
                            </option>
                        </select>
                        <input type="text" name="search_word" style="width: 90%;" class="search_word"
                            value="{{ $search_word ?? '' }}" size="15" /><br>
                        <input type="submit" value="検索" />
                    </form>
                </section>
                @if (env('APP_ENV') == 'production')
                    @yield('sidebar_adsense')
                @endif
                <section class="ranking_menu">
                    <h2>ランキングメニュー</h2>
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
                                        @include('layout.select_year')
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
                                        @include('layout.select_year')
                                        年
                                        <select name="coor" class="coor">
                                            <option value="1"
                                                {{ is_null($coor ?? null) ? '' : ($coor == 1 ? 'selected' : '') }}>冬
                                            </option>
                                            <option value="2"
                                                {{ is_null($coor ?? null) ? '' : ($coor == 2 ? 'selected' : '') }}>春
                                            </option>
                                            <option value="3"
                                                {{ is_null($coor ?? null) ? '' : ($coor == 3 ? 'selected' : '') }}>夏
                                            </option>
                                            <option value="4"
                                                {{ is_null($coor ?? null) ? '' : ($coor == 4 ? 'selected' : '') }}>秋
                                            </option>
                                        </select>
                                        <input type="submit" value="決定" />
                                    </li>
                                </ul>
                            </li>
                        </form>
                    </ul>
                </section>
                <section class="modify_menu">
                    <h2>変更メニュー</h2>
                    <ul>
                        <li><a href="{{ route('add_anime_request.show') }}">アニメの追加申請</a></li>
                        <li><a href="{{ route('add_anime_log.show') }}">アニメの追加履歴</a></li>
                        <li><a href="{{ route('add_cast_request.show') }}">声優の追加申請</a></li>
                        <li><a href="{{ route('add_cast_log.show') }}">声優の追加履歴</a></li>
                        <li><a href="{{ route('add_creater_request.show') }}">クリエイターの追加申請</a></li>
                        <li><a href="{{ route('add_creater_log.show') }}">クリエイターの追加履歴</a></li>
                        <li><a href="{{ route('modify_request_list.show') }}">変更申請リスト</a></li>
                    </ul>
                </section>
                <section class="other_menu">
                    <h2>その他</h2>
                    <ul>
                        <li><a href="{{ route('contact.show') }}">要望フォーム</a></li>
                        <li><a href="{{ route('update_log.show') }}">更新履歴</a></li>
                    </ul>
                </section>
            </aside>
            <main class="col-xl-10 col-sm-9">
                @yield('main_adsense_smartphone')
                @yield('main')
            </main>
        </div>
        <footer>
            <hr>
            <a href="{{ route('site_information.show') }}">このサイトについて</a>
            <a href="{{ route('privacy_policy.show') }}">プライバシーポリシー</a>
            @ 2022 animescape.link
        </footer>
        @yield('vue.js')
    </div>
</body>

</html>
