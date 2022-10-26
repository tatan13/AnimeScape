@extends('layout')

@section('title')
    <title>変更申請リスト AnimeScape</title>
@endsection

@section('main')
    <article class="modify_list">
        <section class="modify_anime_request_list">
            <h2>アニメの基本情報変更申請リスト</h2>
            @if (session('flash_modify_anime_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_modify_anime_request_message') }}
                </div>
            @endif
            @foreach ($modify_anime_request_list as $modify_anime_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('anime.show', ['anime_id' => $modify_anime_request->anime->id]) }}">{{ $modify_anime_request->anime->title }}</a>)
                </h3>
                <form class="modify_anime_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('modify_anime_request.approve', ['modify_anime_id' => $modify_anime_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('modify_anime_request.reject', ['modify_anime_id' => $modify_anime_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="modify_anime_request_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>現在の情報</th>
                                <th>訂正情報</th>
                            </tr>
                            <tr>
                                <th>アニメ名</th>
                                <td>{{ $modify_anime_request->anime->title }}</td>
                                <td><input type="text" name="title" value="{{ $modify_anime_request->title }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td>{{ $modify_anime_request->anime->furigana }}</td>
                                <td><input type="text" name="furigana" value="{{ $modify_anime_request->furigana }}"></td>
                            </tr>
                            <tr>
                                <th>略称</th>
                                <td>{{ $modify_anime_request->anime->title_short }}</td>
                                <td><input type="text" name="title_short"
                                        value="{{ $modify_anime_request->title_short }}"></td>
                            </tr>
                            <tr>
                                <th>放送年</th>
                                <td>{{ $modify_anime_request->anime->year }}</td>
                                <td><input type="number" name="year" value="{{ $modify_anime_request->year }}"></td>
                            </tr>
                            <tr>
                                <th>クール</th>
                                <td>{{ $modify_anime_request->anime->coor_label }}</td>
                                <td>
                                    <select name="coor">
                                        <option value="1" {{ $modify_anime_request->coor == 1 ? 'selected' : '' }}>冬
                                        </option>
                                        <option value="2" {{ $modify_anime_request->coor == 2 ? 'selected' : '' }}>春
                                        </option>
                                        <option value="3" {{ $modify_anime_request->coor == 3 ? 'selected' : '' }}>夏
                                        </option>
                                        <option value="4" {{ $modify_anime_request->coor == 4 ? 'selected' : '' }}>秋
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>話数</th>
                                <td>{{ $modify_anime_request->anime->number_of_episode }}</td>
                                <td><input type="number" name="number_of_episode"
                                        value="{{ $modify_anime_request->number_of_episode }}"></td>
                            </tr>
                            <tr>
                                <th>公式HPのURL</th>
                                <td>{{ $modify_anime_request->anime->public_url }}</td>
                                <td><input type="text" name="public_url" value="{{ $modify_anime_request->public_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式twitterID</th>
                                <td>{{ '@' . $modify_anime_request->anime->twitter }}</td>
                                <td>@<input type="text" name="twitter" value="{{ $modify_anime_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ハッシュタグ</th>
                                <td>#{{ $modify_anime_request->anime->hash_tag }}</td>
                                <td>#<input type="text" name="hash_tag" value="{{ $modify_anime_request->hash_tag }}">
                                </td>
                            </tr>
                            <tr>
                                <th>舞台</th>
                                <td>{{ $modify_anime_request->anime->city_name }}</td>
                                <td><input type="text" name="city_name" value="{{ $modify_anime_request->city_name }}">
                                </td>
                            </tr>
                            <tr>
                                <th>制作会社1</th>
                                <td>{{ $modify_anime_request->anime->companies[0]->name ?? '' }}</td>
                                <td><input type="text" name="company1" value="{{ $modify_anime_request->company1 }}">
                                </td>
                            </tr>
                            <tr>
                                <th>制作会社2</th>
                                <td>{{ $modify_anime_request->anime->companies[1]->name ?? '' }}</td>
                                <td><input type="text" name="company2" value="{{ $modify_anime_request->company2 }}">
                                </td>
                            </tr>
                            <tr>
                                <th>制作会社3</th>
                                <td>{{ $modify_anime_request->anime->companies[2]->name ?? '' }}</td>
                                <td><input type="text" name="company3" value="{{ $modify_anime_request->company3 }}">
                                </td>
                            </tr>
                            <tr>
                                <th>放送カテゴリー</th>
                                <td>{{ $modify_anime_request->anime->media_category_label }}</td>
                                <td>
                                    <select name="media_category">
                                        <option value="0"
                                            {{ $modify_anime_request->media_category == 0 ? 'selected' : '' }}>
                                            -
                                        </option>
                                        <option value="1"
                                            {{ $modify_anime_request->media_category == 1 ? 'selected' : '' }}>
                                            TVアニメ
                                        </option>
                                        <option value="2"
                                            {{ $modify_anime_request->media_category == 2 ? 'selected' : '' }}>
                                            アニメ映画
                                        </option>
                                        <option value="3"
                                            {{ $modify_anime_request->media_category == 3 ? 'selected' : '' }}>
                                            OVAアニメ
                                        </option>
                                        <option value="4"
                                            {{ $modify_anime_request->media_category == 4 ? 'selected' : '' }}>
                                            配信
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>dアニメストアのID</th>
                                <td>{{ $modify_anime_request->anime->d_anime_store_id }}</td>
                                <td><input type="text" name="d_anime_store_id"
                                        value="{{ $modify_anime_request->d_anime_store_id }}"></td>
                            </tr>
                            <tr>
                                <th>AmazonプライムビデオのID</th>
                                <td>{{ $modify_anime_request->anime->amazon_prime_video_id }}</td>
                                <td><input type="text" name="amazon_prime_video_id"
                                        value="{{ $modify_anime_request->amazon_prime_video_id }}">
                                </td>
                            </tr>
                            <tr>
                                <th>FODのID</th>
                                <td>{{ $modify_anime_request->anime->fod_id }}</td>
                                <td><input type="text" name="fod_id" value="{{ $modify_anime_request->fod_id }}"></td>
                            </tr>
                            <tr>
                                <th>U-NEXTのID</th>
                                <td>{{ $modify_anime_request->anime->unext_id }}</td>
                                <td><input type="text" name="unext_id" value="{{ $modify_anime_request->unext_id }}">
                                </td>
                            </tr>
                            <tr>
                                <th>ABEMAプレミアムのID</th>
                                <td>{{ $modify_anime_request->anime->abema_id }}</td>
                                <td><input type="text" name="abema_id" value="{{ $modify_anime_request->abema_id }}">
                                </td>
                            </tr>
                            <tr>
                                <th>DISNEY+のID</th>
                                <td>{{ $modify_anime_request->anime->disney_plus_id }}</td>
                                <td><input type="text" name="disney_plus_id"
                                        value="{{ $modify_anime_request->disney_plus_id }}"></td>
                            </tr>
                            <tr>
                                <th>あらすじ</th>
                                <td>{{ $modify_anime_request->anime->summary }}</td>
                                <td>
                                    <textarea name="summary" class="summary" cols="80" rows="5">{{ $modify_anime_request->summary }}</textarea><br>
                                </td>
                            </tr>
                            <tr>
                                <th>事由</th>
                                <td></td>
                                <td>{{ $modify_anime_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="add_anime_request_list">
            <h2>アニメの追加申請リスト</h2>
            @if (session('flash_add_anime_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_add_anime_request_message') }}
                </div>
            @endif
            @foreach ($add_anime_request_list as $add_anime_request)
                <h3>{{ $loop->iteration }}件目</h3>
                <form class="add_anime_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('add_anime_request.approve', ['add_anime_id' => $add_anime_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('add_anime_request.reject', ['add_anime_id' => $add_anime_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="add_anime_request_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>情報</th>
                            </tr>
                            <tr>
                                <th>アニメ名</th>
                                <td><input type="text" name="title" value="{{ $add_anime_request->title }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td><input type="text" name="furigana" value="{{ $add_anime_request->furigana }}"></td>
                            </tr>
                            <tr>
                                <th>略称</th>
                                <td><input type="text" name="title_short" value="{{ $add_anime_request->title_short }}">
                                </td>
                            </tr>
                            <tr>
                                <th>放送年</th>
                                <td><input type="number" name="year" value="{{ $add_anime_request->year }}"></td>
                            </tr>
                            <tr>
                                <th>クール</th>
                                <td>
                                    <select name="coor">
                                        <option value="1" {{ $add_anime_request->coor == 1 ? 'selected' : '' }}>冬
                                        </option>
                                        <option value="2" {{ $add_anime_request->coor == 2 ? 'selected' : '' }}>春
                                        </option>
                                        <option value="3" {{ $add_anime_request->coor == 3 ? 'selected' : '' }}>夏
                                        </option>
                                        <option value="4" {{ $add_anime_request->coor == 4 ? 'selected' : '' }}>秋
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>話数</th>
                                <td><input type="number" name="number_of_episode"
                                        value="{{ $add_anime_request->number_of_episode }}"></td>
                            </tr>
                            <tr>
                                <th>公式HPのURL</th>
                                <td><input type="text" name="public_url" value="{{ $add_anime_request->public_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式twitterID</th>
                                <td>@<input type="text" name="twitter" value="{{ $add_anime_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ハッシュタグ</th>
                                <td><input type="text" name="hash_tag" value="{{ $add_anime_request->hash_tag }}"></td>
                            </tr>
                            <tr>
                                <th>舞台</th>
                                <td><input type="text" name="city_name" value="{{ $add_anime_request->city_name }}">
                                </td>
                            </tr>
                            <tr>
                                <th>制作会社1</th>
                                <td><input type="text" name="company1" value="{{ $add_anime_request->company1 }}"></td>
                            </tr>
                            <tr>
                                <th>制作会社2</th>
                                <td><input type="text" name="company2" value="{{ $add_anime_request->company2 }}"></td>
                            </tr>
                            <tr>
                                <th>制作会社3</th>
                                <td><input type="text" name="company3" value="{{ $add_anime_request->company3 }}"></td>
                            </tr>
                            <tr>
                                <th>放送カテゴリー</th>
                                <td>
                                    <select name="media_category">
                                        <option value="0"
                                            {{ $add_anime_request->media_category == 0 ? 'selected' : '' }}>
                                            -
                                        </option>
                                        <option value="1"
                                            {{ $add_anime_request->media_category == 1 ? 'selected' : '' }}>
                                            TVアニメ
                                        </option>
                                        <option value="2"
                                            {{ $add_anime_request->media_category == 2 ? 'selected' : '' }}>
                                            アニメ映画
                                        </option>
                                        <option value="3"
                                            {{ $add_anime_request->media_category == 3 ? 'selected' : '' }}>
                                            OVAアニメ
                                        </option>
                                        <option value="4"
                                            {{ $add_anime_request->media_category == 4 ? 'selected' : '' }}>
                                            配信
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>dアニメストアのID</th>
                                <td><input type="text" name="d_anime_store_id"
                                        value="{{ $add_anime_request->d_anime_store_id }}"></td>
                            </tr>
                            <tr>
                                <th>AmazonプライムビデオのID</th>
                                <td><input type="text" name="amazon_prime_video_id"
                                        value="{{ $add_anime_request->amazon_prime_video_id }}">
                                </td>
                            </tr>
                            <tr>
                                <th>FODのID</th>
                                <td><input type="text" name="fod_id" value="{{ $add_anime_request->fod_id }}"></td>
                            </tr>
                            <tr>
                                <th>U-NEXTのID</th>
                                <td><input type="text" name="unext_id" value="{{ $add_anime_request->unext_id }}"></td>
                            </tr>
                            <tr>
                                <th>ABEMAプレミアムのID</th>
                                <td><input type="text" name="abema_id" value="{{ $add_anime_request->abema_id }}"></td>
                            </tr>
                            <tr>
                                <th>DISNEY+のID</th>
                                <td><input type="text" name="disney_plus_id"
                                        value="{{ $add_anime_request->disney_plus_id }}"></td>
                            </tr>
                            <tr>
                                <th>あらすじ</th>
                                <td>
                                    <textarea name="summary" class="summary" cols="80" rows="5">{{ $add_anime_request->summary }}</textarea><br>
                                </td>
                            </tr>
                            <tr>
                                <th>事由</th>
                                <td>{{ $add_anime_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="delete_anime_request_list">
            <h2>アニメの削除申請リスト</h2>
            @if (session('flash_delete_anime_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_delete_anime_request_message') }}
                </div>
            @endif
            @foreach ($delete_anime_request_list as $delete_anime_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('anime.show', ['anime_id' => $delete_anime_request->anime->id]) }}">{{ $delete_anime_request->anime->title }}</a>)
                </h3>
                <form class="delete_anime_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('delete_anime_request.approve', ['delete_anime_id' => $delete_anime_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('delete_anime_request.reject', ['delete_anime_id' => $delete_anime_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="delete_anime_request_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>削除事由</th>
                            </tr>
                            <tr>
                                <td>{{ $delete_anime_request->anime->title }}
                                </td>
                                <td>{{ $delete_anime_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="modify_cast_request_list">
            <h2>声優情報変更申請リスト</h2>
            @if (session('flash_modify_cast_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_modify_cast_request_message') }}
                </div>
            @endif
            @foreach ($modify_cast_request_list as $modify_cast_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('cast.show', ['cast_id' => $modify_cast_request->cast->id]) }}">{{ $modify_cast_request->cast->name }}</a>)
                </h3>
                <form class="modify_cast_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('modify_cast_request.approve', ['modify_cast_id' => $modify_cast_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('modify_cast_request.reject', ['modify_cast_id' => $modify_cast_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="modify_cast_request_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>現在の情報</th>
                                <th>訂正情報</th>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td>{{ $modify_cast_request->cast->name }}</td>
                                <td><input type="text" name="name" value="{{ $modify_cast_request->name }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td>{{ $modify_cast_request->cast->furigana }}</td>
                                <td><input type="text" name="furigana" value="{{ $modify_cast_request->furigana }}">
                                </td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $modify_cast_request->cast->sex_label }}</td>
                                <td>
                                    <select name="sex">
                                        <option value="" {{ is_null($modify_cast_request->sex) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="1" {{ $modify_cast_request->sex == 1 ? 'selected' : '' }}>男性
                                        </option>
                                        <option value="2" {{ $modify_cast_request->sex == 2 ? 'selected' : '' }}>女性
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>{{ $modify_cast_request->cast->birth }}</td>
                                <td><input type="text" name="birth" value="{{ $modify_cast_request->birth }}">
                                </td>
                            </tr>
                            <tr>
                                <th>出身地</th>
                                <td>{{ $modify_cast_request->cast->birthplace }}</td>
                                <td><input type="text" name="birthplace" value="{{ $modify_cast_request->birthplace }}">
                                </td>
                            </tr>
                            <tr>
                                <th>血液型</th>
                                <td>{{ $modify_cast_request->cast->blood_type }}</td>
                                <td>
                                    <select name="blood_type">
                                        <option value=""
                                            {{ is_null($modify_cast_request->blood_type) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="A"
                                            {{ $modify_cast_request->blood_type == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B"
                                            {{ $modify_cast_request->blood_type == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="O"
                                            {{ $modify_cast_request->blood_type == 'O' ? 'selected' : '' }}>O
                                        </option>
                                        <option value="AB"
                                            {{ $modify_cast_request->blood_type == 'AB' ? 'selected' : '' }}>AB
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>所属事務所</th>
                                <td>{{ $modify_cast_request->cast->office }}</td>
                                <td><input type="text" name="office" value="{{ $modify_cast_request->office }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式HP</th>
                                <td>{{ $modify_cast_request->cast->url }}</td>
                                <td><input type="text" name="url" value="{{ $modify_cast_request->url }}"></td>
                            </tr>
                            <tr>
                                <th>ツイッター</th>
                                <td>{{ '@' . $modify_cast_request->cast->twitter }}</td>
                                <td>@<input type="text" name="twitter" value="{{ $modify_cast_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログ</th>
                                <td>{{ $modify_cast_request->cast->blog }}</td>
                                <td><input type="text" name="blog" value="{{ $modify_cast_request->blog }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログURL</th>
                                <td>{{ $modify_cast_request->cast->blog_url }}</td>
                                <td><input type="text" name="blog_url" value="{{ $modify_cast_request->blog_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>事由</th>
                                <td></td>
                                <td>{{ $modify_cast_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="add_cast_request_list">
            <h2>声優の追加申請リスト</h2>
            @if (session('flash_add_cast_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_add_cast_request_message') }}
                </div>
            @endif
            @foreach ($add_cast_request_list as $add_cast_request)
                <h3>{{ $loop->iteration }}件目
                </h3>
                <form class="add_cast_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('add_cast_request.approve', ['add_cast_id' => $add_cast_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('add_cast_request.reject', ['add_cast_id' => $add_cast_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="add_cast_request_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>情報</th>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td><input type="text" name="name" value="{{ $add_cast_request->name }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td><input type="text" name="furigana" value="{{ $add_cast_request->furigana }}"></td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>
                                    <select name="sex">
                                        <option value="" {{ is_null($add_cast_request->sex) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="1" {{ $add_cast_request->sex == 1 ? 'selected' : '' }}>男性
                                        </option>
                                        <option value="2" {{ $add_cast_request->sex == 2 ? 'selected' : '' }}>女性
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td><input type="text" name="birth" value="{{ $add_cast_request->birth }}">
                                </td>
                            </tr>
                            <tr>
                                <th>出身地</th>
                                <td><input type="text" name="birthplace" value="{{ $add_cast_request->birthplace }}">
                                </td>
                            </tr>
                            <tr>
                                <th>血液型</th>
                                <td>
                                    <select name="blood_type">
                                        <option value="" {{ is_null($add_cast_request->blood_type) ? 'selected' : '' }}>
                                            -
                                        </option>
                                        <option value="A" {{ $add_cast_request->blood_type == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $add_cast_request->blood_type == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="O" {{ $add_cast_request->blood_type == 'O' ? 'selected' : '' }}>O
                                        </option>
                                        <option value="AB"
                                            {{ $add_cast_request->blood_type == 'AB' ? 'selected' : '' }}>AB
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>所属事務所</th>
                                <td><input type="text" name="office" value="{{ $add_cast_request->office }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式HP</th>
                                <td><input type="text" name="url" value="{{ $add_cast_request->url }}"></td>
                            </tr>
                            <tr>
                                <th>ツイッター</th>
                                <td>@<input type="text" name="twitter" value="{{ $add_cast_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログ</th>
                                <td><input type="text" name="blog" value="{{ $add_cast_request->blog }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログURL</th>
                                <td><input type="text" name="blog_url" value="{{ $add_cast_request->blog_url }}"></td>
                            </tr>
                            <tr>
                                <th>事由</th>
                                <td>{{ $add_cast_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="delete_cast_request_list">
            <h2>声優の削除申請リスト</h2>
            @if (session('flash_delete_cast_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_delete_cast_request_message') }}
                </div>
            @endif
            @foreach ($delete_cast_request_list as $delete_cast_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('cast.show', ['cast_id' => $delete_cast_request->cast->id]) }}">{{ $delete_cast_request->cast->name }}</a>)
                </h3>
                <form class="delete_cast_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('delete_cast_request.approve', ['delete_cast_id' => $delete_cast_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('delete_cast_request.reject', ['delete_cast_id' => $delete_cast_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="delete_cast_request_list_table">
                        <tbody>
                            <tr>
                                <th>声優名</th>
                                <th>削除事由</th>
                            </tr>
                            <tr>
                                <td>{{ $delete_cast_request->cast->name }}</td>
                                <td>{{ $delete_cast_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="modify_creater_request_list">
            <h2>クリエイター情報変更申請リスト</h2>
            @if (session('flash_modify_creater_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_modify_creater_request_message') }}
                </div>
            @endif
            @foreach ($modify_creater_request_list as $modify_creater_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('creater.show', ['creater_id' => $modify_creater_request->creater->id]) }}">{{ $modify_creater_request->creater->name }}</a>)
                </h3>
                <form class="modify_creater_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('modify_creater_request.approve', ['modify_creater_id' => $modify_creater_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('modify_creater_request.reject', ['modify_creater_id' => $modify_creater_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="modify_creater_request_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>現在の情報</th>
                                <th>訂正情報</th>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td>{{ $modify_creater_request->creater->name }}</td>
                                <td><input type="text" name="name" value="{{ $modify_creater_request->name }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td>{{ $modify_creater_request->creater->furigana }}</td>
                                <td><input type="text" name="furigana" value="{{ $modify_creater_request->furigana }}">
                                </td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $modify_creater_request->creater->sex_label }}</td>
                                <td>
                                    <select name="sex">
                                        <option value="" {{ is_null($modify_creater_request->sex) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="1" {{ $modify_creater_request->sex == 1 ? 'selected' : '' }}>男性
                                        </option>
                                        <option value="2" {{ $modify_creater_request->sex == 2 ? 'selected' : '' }}>女性
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>{{ $modify_creater_request->birth }}</td>
                                <td><input type="text" name="birth" value="{{ $modify_creater_request->birth }}">
                                </td>
                            </tr>
                            <tr>
                                <th>出身地</th>
                                <td>{{ $modify_creater_request->birthplace }}</td>
                                <td><input type="text" name="birthplace" value="{{ $modify_creater_request->birthplace }}">
                                </td>
                            </tr>
                            <tr>
                                <th>血液型</th>
                                <td>{{ $modify_creater_request->blood_type }}</td>
                                <td>
                                    <select name="blood_type">
                                        <option value=""
                                            {{ is_null($modify_creater_request->blood_type) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="A"
                                            {{ $modify_creater_request->blood_type == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B"
                                            {{ $modify_creater_request->blood_type == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="O"
                                            {{ $modify_creater_request->blood_type == 'O' ? 'selected' : '' }}>O
                                        </option>
                                        <option value="AB"
                                            {{ $modify_creater_request->blood_type == 'AB' ? 'selected' : '' }}>AB
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>公式HP</th>
                                <td>{{ $modify_creater_request->creater->url }}</td>
                                <td><input type="text" name="url" value="{{ $modify_creater_request->url }}"></td>
                            </tr>
                            <tr>
                                <th>ツイッター</th>
                                <td>{{ '@' . $modify_creater_request->creater->twitter }}</td>
                                <td>@<input type="text" name="twitter" value="{{ $modify_creater_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログ</th>
                                <td>{{ $modify_creater_request->creater->blog }}</td>
                                <td><input type="text" name="blog" value="{{ $modify_creater_request->blog }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログURL</th>
                                <td>{{ $modify_creater_request->blog_url }}</td>
                                <td><input type="text" name="blog_url" value="{{ $modify_creater_request->blog_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>事由</th>
                                <td></td>
                                <td>{{ $modify_creater_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="add_creater_request_list">
            <h2>クリエイターの追加申請リスト</h2>
            @if (session('flash_add_creater_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_add_creater_request_message') }}
                </div>
            @endif
            @foreach ($add_creater_request_list as $add_creater_request)
                <h3>{{ $loop->iteration }}件目
                </h3>
                <form class="add_creater_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('add_creater_request.approve', ['add_creater_id' => $add_creater_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('add_creater_request.reject', ['add_creater_id' => $add_creater_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="add_creater_request_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>情報</th>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td><input type="text" name="name" value="{{ $add_creater_request->name }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td><input type="text" name="furigana" value="{{ $add_creater_request->furigana }}">
                                </td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>
                                    <select name="sex">
                                        <option value="" {{ is_null($add_creater_request->sex) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="1" {{ $add_creater_request->sex == 1 ? 'selected' : '' }}>男性
                                        </option>
                                        <option value="2" {{ $add_creater_request->sex == 2 ? 'selected' : '' }}>女性
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td><input type="text" name="birth" value="{{ $add_creater_request->birth }}">
                                </td>
                            </tr>
                            <tr>
                                <th>出身地</th>
                                <td><input type="text" name="birthplace" value="{{ $add_creater_request->birthplace }}">
                                </td>
                            </tr>
                            <tr>
                                <th>血液型</th>
                                <td>
                                    <select name="blood_type">
                                        <option value=""
                                            {{ is_null($add_creater_request->blood_type) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="A"
                                            {{ $add_creater_request->blood_type == 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B"
                                            {{ $add_creater_request->blood_type == 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="O"
                                            {{ $add_creater_request->blood_type == 'O' ? 'selected' : '' }}>O
                                        </option>
                                        <option value="AB"
                                            {{ $add_creater_request->blood_type == 'AB' ? 'selected' : '' }}>AB
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>公式HP</th>
                                <td><input type="text" name="url" value="{{ $add_creater_request->url }}"></td>
                            </tr>
                            <tr>
                                <th>ツイッター</th>
                                <td>@<input type="text" name="twitter" value="{{ $add_creater_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログ</th>
                                <td><input type="text" name="blog" value="{{ $add_creater_request->blog }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログURL</th>
                                <td><input type="text" name="blog_url" value="{{ $add_creater_request->blog_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>事由</th>
                                <td>{{ $add_creater_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="delete_creater_request_list">
            <h2>クリエイターの削除申請リスト</h2>
            @if (session('flash_delete_creater_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_delete_creater_request_message') }}
                </div>
            @endif
            @foreach ($delete_creater_request_list as $delete_creater_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('creater.show', ['creater_id' => $delete_creater_request->creater->id]) }}">{{ $delete_creater_request->creater->name }}</a>)
                </h3>
                <form class="delete_creater_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('delete_creater_request.approve', ['delete_creater_id' => $delete_creater_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('delete_creater_request.reject', ['delete_creater_id' => $delete_creater_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="delete_creater_request_list_table">
                        <tbody>
                            <tr>
                                <th>クリエイター名</th>
                                <th>削除事由</th>
                            </tr>
                            <tr>
                                <td>{{ $delete_creater_request->creater->name }}</td>
                                <td>{{ $delete_creater_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="delete_company_request_list">
            <h2>会社の削除申請リスト</h2>
            @if (session('flash_delete_company_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_delete_company_request_message') }}
                </div>
            @endif
            @foreach ($delete_company_request_list as $delete_company_request)
                <h3>{{ $loop->iteration }}件目(<a
                        href="{{ route('company.show', ['company_id' => $delete_company_request->company->id]) }}">{{ $delete_company_request->company->name }}</a>)
                </h3>
                <form class="delete_company_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="許可"
                            formaction="{{ route('delete_company_request.approve', ['delete_company_id' => $delete_company_request->id]) }}"
                            formmethod="POST">
                        <input type="submit" value="却下"
                            formaction="{{ route('delete_company_request.reject', ['delete_company_id' => $delete_company_request->id]) }}"
                            formmethod="GET">
                    @endcan
                    <table class="delete_company_request_list_table">
                        <tbody>
                            <tr>
                                <th>会社名</th>
                                <th>削除事由</th>
                            </tr>
                            <tr>
                                <td>{{ $delete_company_request->company->name }}</td>
                                <td>{{ $delete_company_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
    </article>
@endsection
