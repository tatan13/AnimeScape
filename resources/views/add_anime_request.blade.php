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
                        <td><input type="text" name="title" value="{{ old('title') }}"></td>
                    </tr>
                    <tr>
                        <th>ふりがな</th>
                        <td><input type="text" name="furigana" value="{{ old('furigana') }}"></td>
                    </tr>
                    <tr>
                        <th>略称</th>
                        <td><input type="text" name="title_short" value="{{ old('title_short') }}"></td>
                    </tr>
                    <tr>
                        <th>放送年</th>
                        <td><input type="number" name="year" value="{{ old('year') }}"></td>
                    </tr>
                    <tr>
                        <th>クール</th>
                        <td>
                            <select name="coor">
                                <option value="1" {{ old('coor') == 1 ? 'selected' : '' }}>冬
                                </option>
                                <option value="2" {{ old('coor') == 2 ? 'selected' : '' }}>春
                                </option>
                                <option value="3" {{ old('coor') == 3 ? 'selected' : '' }}>夏
                                </option>
                                <option value="4" {{ old('coor') == 4 ? 'selected' : '' }}>秋
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>話数</th>
                        <td><input type="number" name="number_of_episode" value="{{ old('number_of_episode') }}"></td>
                    </tr>
                    <tr>
                        <th>公式HPのURL</th>
                        <td><input type="text" name="public_url" value="{{ old('public_url') }}">
                        </td>
                    </tr>
                    <tr>
                        <th>公式twitterID</th>
                        <td>@<input type="text" name="twitter" value="{{ old('twitter') }}"></td>
                    </tr>
                    <tr>
                        <th>公式ハッシュタグ</th>
                        <td><input type="text" name="hash_tag" value="{{ old('hash_tag') }}"></td>
                    </tr>
                    <tr>
                        <th>舞台</th>
                        <td><input type="text" name="city_name" value="{{ old('city_name') }}"></td>
                    </tr>
                    <tr>
                        <th>制作会社1</th>
                        <td><input type="text" name="company1" value="{{ old('company1') }}"></td>
                    </tr>
                    <tr>
                        <th>制作会社2</th>
                        <td><input type="text" name="company2" value="{{ old('company2') }}"></td>
                    </tr>
                    <tr>
                        <th>制作会社3</th>
                        <td><input type="text" name="company3" value="{{ old('company3') }}"></td>
                    </tr>
                    <tr>
                        <th>dアニメストアのID</th>
                        <td><input type="text" name="d_anime_store_id" value="{{ old('d_anime_store_id') }}"></td>
                    </tr>
                    <tr>
                        <th>AmazonプライムビデオのID</th>
                        <td><input type="text" name="amazon_prime_video_id" value="{{ old('amazon_prime_video_id') }}">
                        </td>
                    </tr>
                    <tr>
                        <th>FODのID</th>
                        <td><input type="text" name="fod_id" value="{{ old('fod_id') }}"></td>
                    </tr>
                    <tr>
                        <th>U-NEXTのID</th>
                        <td><input type="text" name="unext_id" value="{{ old('unext_id') }}"></td>
                    </tr>
                    <tr>
                        <th>ABEMAプレミアムのID</th>
                        <td><input type="text" name="abema_id" value="{{ old('abema_id') }}"></td>
                    </tr>
                    <tr>
                        <th>DISNEY+のID</th>
                        <td><input type="text" name="disney_plus_id" value="{{ old('disney_plus_id') }}"></td>
                    </tr>
                    <tr>
                        <th>あらすじ</th>
                        <td>
                            <label for="summary">内容</label><br>
                            <textarea name="summary" class="summary" cols="80" rows="5">{{ old('summary') }}</textarea><br>
                        </td>
                    </tr>
                    <tr>
                        <th>事由</th>
                        <td><input type="text" size="100" name="remark" class="remark" value="{{ old('remark') }}"></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <h3>注意事項</h3>
        事由は400文字以内で入力してください。
    </article>
@endsection
