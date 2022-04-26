@extends('layout')

@section('title')
    <title>個人情報設定 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
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
    <form action="{{ route('user_config.post') }}" method="POST">
        @csrf
        <table>
            <tbody>
                <tr>
                    <th>設定項目</th>
                    <th>設定</th>
                    <th>説明</th>
                </tr>
                <tr>
                    <td>ユーザーID</td>
                    <td>{{ $user->uid }}</td>
                    <td>ユーザー名の変更はできません。</td>
                </tr>
                <tr>
                    <td>メールアドレス</td>
                    <td>
                        <input type="text" name="email" id="email" value="{{ $user->email }}">
                    </td>
                    <td>パスワードを忘れた場合は、こちらのメールアドレス宛に新しいパスワードをお送りいたします。</td>
                </tr>
                <tr>
                    <td>一言</td>
                    <td>
                        <input type="text" name="onewordcomment" id="one_comment_form" value="{{ $user->onewordcomment }}">
                    </td>
                    <td>400文字以下でお願いします。改行したい場合は&lt;br&gt;と書いてください。</td>
                </tr>
                <tr>
                    <td>Twitterのユーザー名</td>
                    <td>
                        <input type="text" name="twitter" id="twitter" value="{{ $user->twitter }}">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>生まれた年（西暦）</td>
                    <td>
                        <input type="number" name="birth" id="birth" value="{{ $user->birth }}">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>性別</td>
                    <td>
                        <select name="sex">
                            <option value="" {{ is_null($user->sex) ? 'selected' : '' }}>-</option>
                            <option value="1" {{ $user->sex == 1 ? 'selected' : '' }}>男性</option>
                            <option value="0" {{ $user->sex == 0 ? 'selected' : '' }}>女性</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="更新">
        </div>
        </div>
    @endsection
