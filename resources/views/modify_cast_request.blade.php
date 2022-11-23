@extends('layout')

@section('title')
    <title>{{ $cast->name }}の情報変更申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="modify_cast_request">
        <h2>{{ $cast->name }}の情報変更申請</h2>
        <h3><a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a></h3>
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
        <form action="{{ route('modify_cast_request.post', ['cast_id' => $cast->id]) }}" class="modify_cast_request_form"
            method="POST">
            @csrf
            <input type="submit" value="送信">
            <table class="modify_cast_request_table">
                <tbody>
                    <tr>
                        <th>項目</th>
                        <th>現在の情報</th>
                        <th>訂正情報</th>
                    </tr>
                    <tr>
                        <th>名前</th>
                        <td>{{ $cast->name }}</td>
                        <td><input type="text" name="name" value="{{ $cast->name }}"></td>
                    </tr>
                    <tr>
                        <th>ふりがな</th>
                        <td>{{ $cast->furigana }}</td>
                        <td><input type="text" name="furigana" value="{{ $cast->furigana }}"></td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>{{ $cast->sex_label }}</td>
                        <td>
                            <select name="sex">
                                <option value="" {{ is_null($cast->sex) ? 'selected' : '' }}>-
                                </option>
                                <option value="1" {{ $cast->sex == 1 ? 'selected' : '' }}>男性
                                </option>
                                <option value="2" {{ $cast->sex == 2 ? 'selected' : '' }}>女性
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td>{{ $cast->birth }}</td>
                        <td><input type="text" name="birth" value="{{ $cast->birth }}">
                        </td>
                    </tr>
                    <tr>
                        <th>出身地</th>
                        <td>{{ $cast->birthplace }}</td>
                        <td><input type="text" name="birthplace" value="{{ $cast->birthplace }}">
                        </td>
                    </tr>
                    <tr>
                        <th>血液型</th>
                        <td>{{ $cast->blood_type }}</td>
                        <td>
                            <select name="blood_type">
                                <option value="" {{ is_null($cast->blood_type) ? 'selected' : '' }}>-
                                </option>
                                <option value="A" {{ $cast->blood_type == 'A' ? 'selected' : '' }}>A
                                </option>
                                <option value="B" {{ $cast->blood_type == 'B' ? 'selected' : '' }}>B
                                </option>
                                <option value="O" {{ $cast->blood_type == 'O' ? 'selected' : '' }}>O
                                </option>
                                <option value="AB" {{ $cast->blood_type == 'AB' ? 'selected' : '' }}>AB
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>所属事務所</th>
                        <td>{{ $cast->office }}</td>
                        <td><input type="text" name="office" value="{{ $cast->office }}">
                        </td>
                    </tr>
                    <tr>
                        <th>公式HP</th>
                        <td>{{ $cast->url }}</td>
                        <td><input type="text" name="url" value="{{ $cast->url }}"></td>
                    </tr>
                    <tr>
                        <th>ツイッター</th>
                        <td>{{ '@' . $cast->twitter }}</td>
                        <td>@<input type="text" name="twitter" value="{{ $cast->twitter }}"></td>
                    </tr>
                    <tr>
                        <th>公式ブログ名</th>
                        <td>{{ $cast->blog }}</td>
                        <td><input type="text" name="blog" value="{{ $cast->blog }}"></td>
                    </tr>
                    <tr>
                        <th>公式ブログURL</th>
                        <td>{{ $cast->blog_url }}</td>
                        <td><input type="text" name="blog_url" value="{{ $cast->blog_url }}"></td>
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
        <ul class="list-inline">
            <li>すべて入力する必要はありません。可能な限りでご協力お願いします。</li>
            <li>生年月日は〇年〇月〇日で入力してください。生まれ年が不明の場合は〇月〇日で入力してください。</li>
            <li>ふりがなはすべてひらがなでお願いします。</li>
            <li>事由は400文字以内で入力してください。</li>
        </ul>
    </article>
@endsection
