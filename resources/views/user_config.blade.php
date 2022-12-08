@extends('layout')

@section('title')
    <title>個人情報設定 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="user_config">
        <h1>個人情報設定</h1>
        <h2>設定一覧</h2>
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
        @if (session('twitter_unlink'))
            <div class="alert alert-success">
                {{ session('twitter_unlink') }}
            </div>
        @endif
        <form action="{{ route('user_config.post') }}" class="user_config_form" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="user_config_table">
                    <tbody>
                        <tr>
                            <th>設定項目</th>
                            <th>設定</th>
                            <th>説明</th>
                        </tr>
                        <tr>
                            <td>ユーザー名</td>
                            <td>
                                <input type="text" name="name" class="name" value="{{ $user->name }}">
                            </td>
                            <td>他のユーザー名と被らないようにしてください。</td>
                        </tr>
                        <tr>
                            <td>メールアドレス</td>
                            <td>
                                <input type="text" name="email" class="email" value="{{ $user->email }}">
                            </td>
                            <td>パスワード再設定やTwitter連携の方の通常ログイン用のパスワードはこちらのメールアドレス宛にパスワードをお送りいたします。</td>
                        </tr>
                        <tr>
                            <td>一言</td>
                            <td>
                                <textarea name="one_comment" class="one_comment_form" rows="3" cols="100">{{ $user->one_comment }}</textarea><br>
                            </td>
                            <td>400文字以下でお願いします。</td>
                        </tr>
                        <tr>
                            <td>Twitterのユーザー名</td>
                            <td>
                                @<input type="text" name="twitter" class="twitter" value="{{ $user->twitter }}">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>生まれた年（西暦）</td>
                            <td>
                                <input type="number" name="birth" class="birth" value="{{ $user->birth }}">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>性別</td>
                            <td>
                                <select name="sex">
                                    <option value="" {{ is_null($user->sex) ? 'selected' : '' }}>-</option>
                                    <option value="1" {{ $user->sex == 1 ? 'selected' : '' }}>男性</option>
                                    <option value="2" {{ $user->sex == 2 ? 'selected' : '' }}>女性</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Twitter連携</td>
                            <td>
                                @if (is_null($user->unique_id))
                                    連携なし
                                    <a href="{{ route('provider.redirect', ['provider' => 'twitter']) }}">
                                        Twitter連携する
                                    </a>
                                @else
                                    連携済み
                                    <a href="{{ route('user_twitter.unlink') }}" onclick="return confirm('本当に解除しますか？')">
                                        Twitter連携を解除する
                                    </a>
                                @endif
                            </td>
                            <td>Twitterアカウント一つにつき連携できるアカウントは一つです。</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="submit" value="更新">
    </article>
@endsection
