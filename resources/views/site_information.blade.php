@extends('layout')

@section('title')
    <title>このサイトについて AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <article class="site_information">
        <h1>このサイトについて</h1>
        <section class="information">
            <h2>AnimeScape -アニメ批評空間-</h2>
            IDを作成し、ログインすることで各アニメの点数や感想をつけることができる統計サイトです。
            また、他人のアカウントをお気に入りユーザーとして登録することができ、お気に入りユーザー内での点数評価の統計を取ることで、次に視聴するアニメを決定する情報として使用することができます。
        </section>
        <section class="contact_information">
            <h2>お問い合わせ</h2>
            このサイトに関するお問い合わせは以下のTwitterアカウントにご連絡ください。<br>
            <a href="https://twitter.com/tatan_tech" target="_blank" rel="noopener noreferrer">@tatan_tech</a>
        </section>
    </article>
@endsection
