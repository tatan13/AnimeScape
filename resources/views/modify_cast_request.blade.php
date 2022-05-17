@extends('layout')

@section('title')
    <title>{{ $cast->name }}の情報変更申請 AnimeScape</title>
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
        <form action="{{ route('modify_cast_request.post', ['cast_id' => $cast->id]) }}" class="modify_cast_request_form" method="POST">
            @csrf
            <input type="submit" value="登録">
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
                                <option value="2" {{ $cast->coor == 2 ? 'selected' : '' }}>女性
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
                        <th>公式ブログ</th>
                        <td>{{ $cast->blog }}</td>
                        <td><input type="text" name="blog" value="{{ $cast->blog }}"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </article>
@endsection
