@extends('layout')

@section('title')
    <title>要望フォーム AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('title_adsense')
    @include('layout.horizontal_adsense')
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main_adsense_smartphone')
    @include('layout.horizontal_adsense_smartphone')
@endsection

@section('main')
    <article class="contact">
        <h1>要望フォーム</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        名前を書かないと名無しさんになります。
        <form action="{{ route('contact.post') }}" class="contact_form" method="POST">
            @csrf
            <label for="name">名前</label><br>
            <input type="text" size="15" name="name" class="name"><br>
            <label for="comment">内容</label><br>
            <textarea name="comment" class="comment" cols="80" rows="5"></textarea><br>
            <label for="auth">認証</label><br>
            <input type="text" size="15" name="auth" class="auth">「にんしょう」と入力してください。SPAM対策です。<br>
            <input type="submit" value="書き込む">
        </form>
        <hr>
        @foreach ($contacts as $contact)
            <strong>{{ $contact->name }}</strong><br>
            {{ $contact->created_at }}<br>
            {!! nl2br(e($contact->comment)) !!}
            <hr>
        @endforeach
    </article>
@endsection
