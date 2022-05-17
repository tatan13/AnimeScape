@extends('layout')

@section('title')
    <title>アニメの追加申請 AnimeScape</title>
@endsection

@section('main')
    <article class="add_anime_request">
        <h2>アニメの追加申請</h2>
        <h3>申請フォーム</h3>
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
        <form action="{{ route('add_anime_request.post') }}" class="add_anime_request_form" method="POST">
            @csrf
            <input type="submit" value="登録">
            <table class="add_anime_request_table">
                <tbody>
                    <tr>
                        <th>項目</th>
                        <th>情報</th>
                    </tr>
                    <tr>
                        <th>アニメ名</th>
                        <td><input type="text" name="title" value=""></td>
                    </tr>
                    <tr>
                        <th>放送年</th>
                        <td><input type="number" name="year" value=""></td>
                    </tr>
                    <tr>
                        <th>クール</th>
                        <td>
                            <select name="coor">
                                <option value="1">冬
                                </option>
                                <option value="2">春
                                </option>
                                <option value="3">夏
                                </option>
                                <option value="4">秋
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>公式HPのURL</th>
                        <td><input type="text" name="public_url" value="">
                        </td>
                    </tr>
                    <tr>
                        <th>公式twitterID</th>
                        <td>@<input type="text" name="twitter" value=""></td>
                    </tr>
                    <tr>
                        <th>公式ハッシュタグ</th>
                        <td><input type="text" name="hash_tag" value=""></td>
                    </tr>
                    <tr>
                        <th>制作会社</th>
                        <td><input type="text" name="company" value=""></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </article>
@endsection