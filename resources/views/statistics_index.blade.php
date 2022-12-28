@extends('layout')

@section('title')
    <title>ランキングメニュー AnimeScape -アニメ批評空間-</title>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('statistics_index') }}
@endsection

@section('main')
    <article class="statistics_index">
        <h1>ランキングメニュー</h1>
        <section class="statistics_menu">
            <h2>メニュー</h2>
            <ul>
                <li>
                    <form action="{{ route('anime_statistics.show') }}" name="anime_statistics" method="get">
                        @csrf
                        <a href="javascript:anime_statistics.submit()">アニメ</a><br>
                        <select name="year">
                            <option value="">-</option>
                            @include('layout/select_year')
                        </select>
                        年
                        <select name="coor">
                            <option value="">-</option>
                            <option value="1">冬</option>
                            <option value="2">春</option>
                            <option value="3">夏</option>
                            <option value="4">秋</option>
                        </select>クールの
                        得点数が
                        <input type="number" name="count" value="1">
                        以上のアニメで
                        <select name="category">
                            <option value="median">中央値</option>
                            <option value="average">平均値</option>
                            <option value="count">得点数</option>
                        </select>
                        順に
                        <input type="submit" value="絞りこむ">
                    </form>
                </li>
                <li>
                    <form action="{{ route('cast_statistics.show') }}" name="cast_statistics" method="get">
                        @csrf
                        <a href="javascript:cast_statistics.submit()">声優</a><br>
                        <select name="year">
                            <option value="">-</option>
                            @include('layout/select_year')
                        </select>
                        年
                        <select name="coor">
                            <option value="">-</option>
                            <option value="1">冬</option>
                            <option value="2">春</option>
                            <option value="3">夏</option>
                            <option value="4">秋</option>
                        </select>クールの
                        得点数が
                        <input type="number" name="count">
                        以上のアニメに出演した声優で
                        <select name="category" class="category">
                            <option value="score_median">中央値</option>
                            <option value="score_average">平均値</option>
                            <option value="act_animes_count">出演数</option>
                            <option value="score_count">総得点数</option>
                            <option value="score_users_count">総得点ユーザー数</option>
                            <option value="liked_users_count">お気に入りユーザー数</option>
                        </select>
                        順に
                        <input type="submit" value="絞りこむ">
                    </form>
                </li>
                <li>
                    <form action="{{ route('company_statistics.show') }}" name="company_statistics" method="get">
                        @csrf
                        <a href="javascript:company_statistics.submit()">制作会社</a><br>
                        <select name="year">
                            <option value="">-</option>
                            @include('layout/select_year')
                        </select>
                        年
                        <select name="coor">
                            <option value="">-</option>
                            <option value="1">冬</option>
                            <option value="2">春</option>
                            <option value="3">夏</option>
                            <option value="4">秋</option>
                        </select>クールの
                        得点数が
                        <input type="number" name="count">
                        以上のアニメを制作した会社で
                        <select name="category" class="category">
                            <option value="score_median">中央値</option>
                            <option value="score_average">平均値</option>
                            <option value="animes_count">制作数</option>
                            <option value="score_count">総得点数</option>
                            <option value="score_users_count">総得点ユーザー数</option>
                        </select>
                        順に
                        <input type="submit" value="絞りこむ">
                    </form>
                </li>
            </ul>
        </section>
    </article>
@endsection
