@extends('layout')

@section('title')
    <title>個人情報設定 AnimeScape</title>
@endsection

@section('main')
    <article class="user_config">
        <h2>個人情報設定</h2>
        <h3>設定一覧</h3>
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
        <form action="{{ route('user_config.post') }}" class="user_config_form" method="POST">
            @csrf
            <table class="user_config_table">
                <tbody>
                    <tr>
                        <th>設定項目</th>
                        <th>設定</th>
                        <th>説明</th>
                    </tr>
                    <tr>
                        <td>ユーザーID</td>
                        <td>{{ $user->name }}</td>
                        <td>ユーザー名の変更はできません。</td>
                    </tr>
                    <tr>
                        <td>メールアドレス</td>
                        <td>
                            <input type="text" name="email" class="email" value="{{ $user->email }}">
                        </td>
                        <td>パスワードを忘れた場合は、こちらのメールアドレス宛に新しいパスワードをお送りいたします。</td>
                    </tr>
                    <tr>
                        <td>一言</td>
                        <td>
                            <textarea name="one_comment" class="one_comment_form" cols="40" rows="3">{{ $user->one_comment }}</textarea><br>
                        </td>
                        <td>400文字以下でお願いします。</td>
                    </tr>
                    <tr>
                        <td>Twitterのユーザー名</td>
                        <td>
                            <input type="text" name="twitter" class="twitter" value="{{ $user->twitter }}">
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
                </tbody>
            </table>
            <input type="submit" value="更新">
    </article>
@endsection
