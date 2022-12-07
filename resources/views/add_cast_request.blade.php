@extends('layout')

@section('title')
    <title>声優の追加申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="add_cast_request">
        <h1>声優の追加申請</h1>
        <h2>申請フォーム</h2>
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
        <form action="{{ route('add_cast_request.post') }}" class="add_cast_request_form" method="POST">
            @csrf
            <input type="submit" value="送信">
            <div class="table-responsive">
                <table class="add_cast_request_table">
                    <tbody>
                        <tr>
                            <th>項目</th>
                            <th>情報</th>
                        </tr>
                        <tr>
                            <th>名前</th>
                            <td><input type="text" name="name" value=""></td>
                        </tr>
                        <tr>
                            <th>ふりがな</th>
                            <td><input type="text" name="furigana" value=""></td>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td>
                                <select name="sex">
                                    <option value="">-
                                    </option>
                                    <option value="1">男性
                                    </option>
                                    <option value="2">女性
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>生年月日</th>
                            <td><input type="text" name="birth" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>出身地</th>
                            <td><input type="text" name="birthplace" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>血液型</th>
                            <td>
                                <select name="blood_type">
                                    <option value="">-
                                    </option>
                                    <option value="A">A
                                    </option>
                                    <option value="B">B
                                    </option>
                                    <option value="O">O
                                    </option>
                                    <option value="AB">AB
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>所属事務所</th>
                            <td><input type="text" name="office" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>公式HP</th>
                            <td><input type="text" name="url" value=""></td>
                        </tr>
                        <tr>
                            <th>ツイッター</th>
                            <td>@<input type="text" name="twitter" value=""></td>
                        </tr>
                        <tr>
                            <th>公式ブログ名</th>
                            <td><input type="text" name="blog" value=""></td>
                        </tr>
                        <tr>
                            <th>公式ブログURL</th>
                            <td><input type="text" name="blog_url" value=""></td>
                        </tr>
                        <tr>
                            <th>事由</th>
                            <td><input type="text" name="remark" size="100" class="remark" style="width: 100%"
                                    value="{{ old('remark') }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <h2>注意事項</h2>
        <ul class="list-inline">
            <li>すべて入力する必要はありません。可能な限りでご協力お願いします。</li>
            <li>生年月日は〇年〇月〇日で入力してください。生まれ年が不明の場合は〇月〇日で入力してください。</li>
            <li>ふりがなはすべてひらがなでお願いします。</li>
            <li>事由は400文字以内で入力してください。</li>
        </ul>
    </article>
@endsection
