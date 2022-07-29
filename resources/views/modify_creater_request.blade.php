@extends('layout')

@section('title')
    <title>{{ $creater->name }}の情報変更申請 AnimeScape</title>
@endsection

@section('main')
    <article class="modify_creater_request">
        <h2>{{ $creater->name }}の情報変更申請</h2>
        <h3><a href="{{ route('creater.show', ['creater_id' => $creater->id]) }}">{{ $creater->name }}</a></h3>
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
        <form action="{{ route('modify_creater_request.post', ['creater_id' => $creater->id]) }}" class="modify_creater_request_form"
            method="POST">
            @csrf
            <input type="submit" value="送信">
            <table class="modify_creater_request_table">
                <tbody>
                    <tr>
                        <th>項目</th>
                        <th>現在の情報</th>
                        <th>訂正情報</th>
                    </tr>
                    <tr>
                        <th>名前</th>
                        <td>{{ $creater->name }}</td>
                        <td><input type="text" name="name" value="{{ $creater->name }}"></td>
                    </tr>
                    <tr>
                        <th>ふりがな</th>
                        <td>{{ $creater->furigana }}</td>
                        <td><input type="text" name="furigana" value="{{ $creater->furigana }}"></td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>{{ $creater->sex_label }}</td>
                        <td>
                            <select name="sex">
                                <option value="" {{ is_null($creater->sex) ? 'selected' : '' }}>-
                                </option>
                                <option value="1" {{ $creater->sex == 1 ? 'selected' : '' }}>男性
                                </option>
                                <option value="2" {{ $creater->sex == 2 ? 'selected' : '' }}>女性
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td>{{ $creater->birth }}</td>
                        <td><input type="text" name="birth" value="{{ $creater->birth }}">
                        </td>
                    </tr>
                    <tr>
                        <th>出身地</th>
                        <td>{{ $creater->birthplace }}</td>
                        <td><input type="text" name="birthplace" value="{{ $creater->birthplace }}">
                        </td>
                    </tr>
                    <tr>
                        <th>血液型</th>
                        <td>{{ $creater->blood_type }}</td>
                        <td>
                            <select name="blood_type">
                                <option value="" {{ is_null($creater->blood_type) ? 'selected' : '' }}>-
                                </option>
                                <option value="A" {{ $creater->blood_type == 'A' ? 'selected' : '' }}>A
                                </option>
                                <option value="B" {{ $creater->blood_type == 'B' ? 'selected' : '' }}>B
                                </option>
                                <option value="O" {{ $creater->blood_type == 'O' ? 'selected' : '' }}>O
                                </option>
                                <option value="AB" {{ $creater->blood_type == 'AB' ? 'selected' : '' }}>AB
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>公式HP</th>
                        <td>{{ $creater->url }}</td>
                        <td><input type="text" name="url" value="{{ $creater->url }}"></td>
                    </tr>
                    <tr>
                        <th>ツイッター</th>
                        <td>{{ '@' . $creater->twitter }}</td>
                        <td>@<input type="text" name="twitter" value="{{ $creater->twitter }}"></td>
                    </tr>
                    <tr>
                        <th>公式ブログ名</th>
                        <td>{{ $creater->blog }}</td>
                        <td><input type="text" name="blog" value="{{ $creater->blog }}"></td>
                    </tr>
                    <tr>
                        <th>公式ブログURL</th>
                        <td>{{ $creater->blog_url }}</td>
                        <td><input type="text" name="blog_url" value="{{ $creater->blog_url }}"></td>
                    </tr>
                    <tr>
                        <th>事由</th>
                        <td></td>
                        <td><input type="text" size="100" name="remark" class="remark"
                                value="{{ old('remark') }}"></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <h3>注意事項</h3>
        事由は400文字以内で入力してください。<br>
        生年月日は〇年〇月〇日で入力してください。生まれ年が不明の場合は〇月〇日で入力してください。
    </article>
@endsection