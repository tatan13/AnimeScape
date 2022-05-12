@extends('layout')

@section('title')
    <title>このサイトについて AnimeScape</title>
@endsection

@section('main')
    <article class="site_information">
        <h2>このサイトについて</h2>
        <section class="information">
            <h3>AnimeScape</h3>
            IDを作成し、ログインすることで各アニメの点数や感想をつけることができる統計サイトです。
            また、他人のアカウントをお気に入りユーザーとして登録することができ、お気に入りユーザー内での点数評価の統計を取ることで、次に視聴するアニメを決定する情報として使用することができます。
        </section>
        <section class="contact_information">
            <h3>お問い合わせ</h3>
            このサイトに関するお問い合わせは以下のTwitterアカウントにご連絡ください。<br>
            <a href="https://twitter.com/tatan_tech" target="_blank" rel="noopener noreferrer">@tatan_tech</a>
        </section>
    </article>
@endsection
