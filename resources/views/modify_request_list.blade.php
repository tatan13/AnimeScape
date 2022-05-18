@extends('layout')

@section('title')
    <title>変更真申請リスト AnimeScape</title>
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
                <h3>{{ $loop->iteration }}件目</h3>
                <form class="modify_anime_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="登録"
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
                                <td><input type="text" name="title" value="{{ $modify_anime_request->title }}">
                                </td>
                            </tr>
                            <tr>
                                <th>略称</th>
                                <td>{{ $modify_anime_request->anime->title_short }}</td>
                                <td><input type="text" name="title_short"
                                        value="{{ $modify_anime_request->title_short }}">
                                </td>
                            </tr>
                            <tr>
                                <th>放送年</th>
                                <td>{{ $modify_anime_request->anime->year }}</td>
                                <td><input type="number" name="year" value="{{ $modify_anime_request->year }}">
                                </td>
                            </tr>
                            <tr>
                                <th>クール</th>
                                <td>{{ $modify_anime_request->anime->coor_label }}
                                </td>
                                <td>
                                    <select name="coor" class="coor">
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
                                <th>公式HPのURL</th>
                                <td>{{ $modify_anime_request->anime->public_url }}
                                </td>
                                <td><input type="text" name="public_url" value="{{ $modify_anime_request->public_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式twitterID</th>
                                <td>@ {{ $modify_anime_request->anime->twitter }}</td>
                                <td>@<input type="text" name="twitter" value="{{ $modify_anime_request->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ハッシュタグ</th>
                                <td>{{ $modify_anime_request->anime->hash_tag }}</td>
                                <td><input type="text" name="hash_tag" value="{{ $modify_anime_request->hash_tag }}">
                                </td>
                            </tr>
                            <tr>
                                <th>制作会社</th>
                                <td>{{ $modify_anime_request->anime->company }}</td>
                                <td><input type="text" name="company" value="{{ $modify_anime_request->company }}"></td>
                            </tr>
                            <tr>
                                <th>舞台</th>
                                <td>{{ $modify_anime_request->anime->city_name }}</td>
                                <td><input type="text" name="city_name" value="{{ $modify_anime_request->city_name }}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="modify_occupation_request_list">
            <h2>アニメの出演声優情報変更申請リスト</h2>
            @if (session('flash_modify_occupations_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_modify_occupations_request_message') }}
                </div>
            @endif
            @foreach ($anime_list as $anime)
                <h3>{{ $loop->iteration }}件目({{ $anime->title }})</h3>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2">
                            <table class="occupation_list_table">
                                <tbody>
                                    <th>現在の情報</th>
                                    @foreach ($anime->occupations as $occupation)
                                        <tr>
                                            <td>{{ $occupation->cast->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2">
                            <form class="occupation_list_form">
                                @csrf
                                <table class="modify_occupation_request_list_table">
                                    <tbody>
                                        <th>変更申請情報</th>
                                        @foreach ($anime->modifyOccupations as $modify_occupation_request)
                                            <tr>
                                                <td><input type="text" name="cast_name_{{ $loop->iteration }}"
                                                        value="{{ $modify_occupation_request->cast_name }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @can('isAdmin')
                                    <input type="submit" value="登録"
                                        formaction="{{ route('modify_occupations_request.approve', ['anime_id' => $anime->id]) }}"
                                        formmethod="POST">
                                    <input type="submit" value="却下"
                                        formaction="{{ route('modify_occupations_request.reject', ['anime_id' => $anime->id]) }}"
                                        formmethod="GET">
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
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
                <h3>{{ $loop->iteration }}件目</h3>
                <form class="modify_cast_request_list_form">
                    @csrf
                    @can('isAdmin')
                        <input type="submit" value="登録"
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
                                <td><input type="text" name="furigana" value="{{ $modify_cast_request->furigana }}"></td>
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
                                        <option value="2" {{ $modify_cast_request->coor == 2 ? 'selected' : '' }}>女性
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
                <h3>{{ $loop->iteration }}件目</h3>
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
                                <td><a
                                        href="{{ route('anime.show', ['anime_id' => $delete_anime_request->anime->id]) }}">{{ $delete_anime_request->anime->title }}</a>
                                </td>
                                <td>{{ $delete_anime_request->remark }}</td>
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
                        <input type="submit" value="登録"
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
                                <td><input type="text" name="title" value="{{ $add_anime_request->title }}">
                                </td>
                            </tr>
                            <tr>
                                <th>略称</th>
                                <td><input type="text" name="title_short" value="{{ $add_anime_request->title_short }}">
                                </td>
                            </tr>
                            <tr>
                                <th>放送年</th>
                                <td><input type="number" name="year" value="{{ $add_anime_request->year }}">
                                </td>
                            </tr>
                            <tr>
                                <th>クール</th>
                                <td>
                                    <select name="coor" class="coor">
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
                                <th>制作会社</th>
                                <td><input type="text" name="company" value="{{ $add_anime_request->company }}"></td>
                            </tr>
                            <tr>
                                <th>舞台</th>
                                <td><input type="text" name="city_name" value="{{ $add_anime_request->city_name }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="delete_cast_request_list">
            <h2>アニメの削除申請リスト</h2>
            @if (session('flash_delete_cast_request_message'))
                <div class="alert alert-success">
                    {{ session('flash_delete_cast_request_message') }}
                </div>
            @endif
            @foreach ($delete_cast_request_list as $delete_cast_request)
                <h3>{{ $loop->iteration }}件目</h3>
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
                                <td><a
                                        href="{{ route('cast.show', ['cast_id' => $delete_cast_request->cast->id]) }}">{{ $delete_cast_request->cast->name }}</a>
                                </td>
                                <td>{{ $delete_cast_request->remark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
    </article>
@endsection
