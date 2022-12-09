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
    <div class="container">
        <header>
            <div class="d-flex justify-content-between" style="height: 100%;">
                <div class="animescape"><a href="{{ route('index.show') }}">AnimeScape</a></div>
                <div class="login_menu" style="height: 100%;">
                    @if (Auth::check())
                        <form action="{{ route('logout') }}" name="logout" method="POST">
                            @csrf
                            <a href="javascript:logout.submit()" class="btn btn-primary">ログアウト</a>
                        </form>
                    @else
                        <a href="{{ route('provider.redirect', ['provider' => 'twitter']) }}"><img src="twitter_icon.png" alt="Twitterログイン"></a>
                    @endif
                </div>
            </div>
        </header>
        <nav class="search_menu text-end mb-2">
            <form action="{{ route('search.show') }}" method="get">
                @csrf
                <select name="category">
                    <option value="anime"
                        {{ is_null($category ?? null) ? '' : ($category == 'anime' ? 'selected' : '') }}>
                        アニメ
                    </option>
                    <option value="cast"
                        {{ is_null($category ?? null) ? '' : ($category == 'cast' ? 'selected' : '') }}>
                        声優
                    </option>
                    <option value="creater"
                        {{ is_null($category ?? null) ? '' : ($category == 'creater' ? 'selected' : '') }}>
                        クリエイター
                    </option>
                    <option value="company"
                        {{ is_null($category ?? null) ? '' : ($category == 'company' ? 'selected' : '') }}>
                        制作会社
                    </option>
                    <option value="tag"
                        {{ is_null($category ?? null) ? '' : ($category == 'tag' ? 'selected' : '') }}>
                        タグ
                    </option>
                    <option value="user"
                        {{ is_null($category ?? null) ? '' : ($category == 'user' ? 'selected' : '') }}>
                        ユーザー
                    </option>
                </select>
                <input type="text" name="search_word" class="search_word" placeholder="検索"
                    value="{{ $search_word ?? '' }}" />
            </form>
        </nav>
        <div class="row" style="position: relative; min-height: 100vh;">
            <input id="menu" type="checkbox" />
            <label for="menu" class="back"></label>
            <aside class="col-xl-2 col-sm-3">
                <h1>メニュー</h1>
                <nav class="login_menu">
                    <h2>ログインメニュー</h2>
                    @if (Auth::check())
                        ログイン中 : {{ Auth::user()->name }}
                        <ul>
                            <li>
                                <a href="{{ route('user.show', ['user_id' => auth()->user()->id]) }}">マイページ</a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('anime_review_list.show', [
                                        'year' => \App\Models\Anime::NOW_YEAR,
                                        'coor' => \App\Models\Anime::NOW_COOR,
                                    ]) }}">得点一括入力</a>
                            </li>
                            <li>
                                <a href=" {{ route('user_statistics.show', ['user_id' => Auth::id()]) }} ">統計表</a>
                            </li>
                        </ul>
                    @else
                        <ul>
                            <li>
                                <a href="{{ route('login') }}">ログイン</a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" target="_self">新規作成</a>
                            </li>
                        </ul>
                    @endif
                </nav>
                <nav class="normal_menu">
                    <h2>メニュー</h2>
                    <ul>
                        <li>
                            <a href="{{ route('anime_statistics.show') }}">ランキング</a>
                        </li>
                    </ul>
                </nav>
                <nav class="modify_menu">
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
                </nav>
                <nav class="other_menu">
                    <h2>その他</h2>
                    <ul>
                        <li><a href="{{ route('contact.show') }}">要望フォーム</a></li>
                        <li><a href="{{ route('update_log.show') }}">更新履歴</a></li>
                        <li><a href="{{ route('site_information.show') }}">このサイトについて</a></li>
                        <li><a href="{{ route('privacy_policy.show') }}">プライバシーポリシー</a></li>
                    </ul>
                </nav>
                @if (env('APP_ENV') == 'production')
                    @yield('sidebar_adsense')
                @endif
            </aside>
            <main class="col-xl-10 col-sm-9">
                @yield('main_adsense_smartphone')
                @yield('main')
            </main>
        </div>
    </div>
    <footer>
        @ 2022 animescape.link　<a href="https://twitter.com/tatan_tech" target="_blank"
            rel="noopener noreferrer">@tatan_tech</a>
    </footer>
    <nav class="footer_nav">
        <ul class="list-inline">
            <li class="border"><label for="menu" class="open footer_link">メニュー</label></li>
            @if (Auth::check())
                <li class="border"><a href="{{ route('user.show', ['user_id' => auth()->user()->id]) }}"
                        class="footer_link">マイページ</a>
                </li>
                <li class="border">                                <a
                    href="{{ route('anime_review_list.show', [
                        'year' => \App\Models\Anime::NOW_YEAR,
                        'coor' => \App\Models\Anime::NOW_COOR,
                    ]) }} " class="footer_link">得点一括入力</a>
                </li>
            @else
                <li class="border"><a href="{{ route('login') }}" class="footer_link">ログイン</a></li>
                <li class="border"><a href="{{ route('register') }}" target="_self" class="footer_link">新規作成</a>
                </li>
            @endif
            <li class="border"><a href="{{ route('anime_statistics.show') }}" class="footer_link">ランキング</a></li>
        </ul>
    </nav>

    @yield('vue.js')
</body>

</html>
