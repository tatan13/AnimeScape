@extends('layout')

@section('title')
    <title>基本情報変更申請リスト AnimeScape</title>
@endsection

@section('main')
    <article class="modify_list">
        <section class="modify_anime_list">
            <h2>アニメの基本情報変更申請リスト</h2>
            @if (session('flash_anime_message'))
                <div class="alert alert-success">
                    {{ session('flash_anime_message') }}
                </div>
            @endif
            @foreach ($modify_anime_list as $modify_anime)
                <h3>{{ $loop->iteration }}件目</h3>
                <form class="modify_anime_list_form">
                    @csrf
                    <input type="submit" value="登録"
                        formaction="{{ route('modify_anime.update', ['id' => $modify_anime->id]) }}" formmethod="POST">
                    <input type="submit" value="却下"
                        formaction="{{ route('modify_anime.delete', ['id' => $modify_anime->id]) }}" formmethod="GET">
                    <table class="modify_anime_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>現在の情報</th>
                                <th>訂正情報</th>
                            </tr>
                            <tr>
                                <th>アニメ名</th>
                                <td>{{ $modify_anime->anime->title }}</td>
                                <td><input type="text" name="title" value="{{ $modify_anime->title }}">
                                </td>
                            </tr>
                            <tr>
                                <th>略称</th>
                                <td>{{ $modify_anime->anime->title_short }}</td>
                                <td><input type="text" name="title_short" value="{{ $modify_anime->title_short }}">
                                </td>
                            </tr>
                            <tr>
                                <th>放送年</th>
                                <td>{{ $modify_anime->anime->year }}</td>
                                <td><input type="number" name="year" value="{{ $modify_anime->year }}">
                                </td>
                            </tr>
                            <tr>
                                <th>クール</th>
                                <td>{{ $modify_anime->anime->coor_label }}
                                </td>
                                <td>
                                    <select name="coor" class="coor">
                                        <option value="1" {{ $modify_anime->coor == 1 ? 'selected' : '' }}>冬
                                        </option>
                                        <option value="2" {{ $modify_anime->coor == 2 ? 'selected' : '' }}>春
                                        </option>
                                        <option value="3" {{ $modify_anime->coor == 3 ? 'selected' : '' }}>夏
                                        </option>
                                        <option value="4" {{ $modify_anime->coor == 4 ? 'selected' : '' }}>秋
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>公式HPのURL</th>
                                <td>{{ $modify_anime->anime->public_url }}
                                </td>
                                <td><input type="text" name="public_url" value="{{ $modify_anime->public_url }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式twitterID</th>
                                <td>@ {{ $modify_anime->anime->twitter }}</td>
                                <td>@<input type="text" name="twitter" value="{{ $modify_anime->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ハッシュタグ</th>
                                <td>{{ $modify_anime->anime->hash_tag }}</td>
                                <td><input type="text" name="hash_tag" value="{{ $modify_anime->hash_tag }}"></td>
                            </tr>
                            <tr>
                                <th>制作会社</th>
                                <td>{{ $modify_anime->anime->company }}</td>
                                <td><input type="text" name="company" value="{{ $modify_anime->company }}"></td>
                            </tr>
                            <tr>
                                <th>舞台</th>
                                <td>{{ $modify_anime->anime->city_name }}</td>
                                <td><input type="text" name="city_name" value="{{ $modify_anime->city_name }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
        </section>
        <section class="modify_occupation_list">
            <h2>アニメの出演声優情報変更申請リスト</h2>
            @if (session('flash_occupation_message'))
                <div class="alert alert-success">
                    {{ session('flash_occupation_message') }}
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
                                <table class="modify_occupation_list_table">
                                    <tbody>
                                        <th>変更依頼情報</th>
                                        @foreach ($anime->modifyOccupations as $modify_occupation)
                                            <tr>
                                                <td><input type="text" name="cast_name_{{ $loop->iteration }}"
                                                        value="{{ $modify_occupation->cast_name }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="submit" value="登録"
                                    formaction="{{ route('modify_occupation.update', ['id' => $anime->id]) }}"
                                    formmethod="POST">
                                <input type="submit" value="却下"
                                    formaction="{{ route('modify_occupation.delete', ['id' => $anime->id]) }}"
                                    formmethod="GET">
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
        <section class="modify_cast_list">
            <h2>声優情報変更申請リスト</h2>
            @if (session('flash_cast_message'))
                <div class="alert alert-success">
                    {{ session('flash_cast_message') }}
                </div>
            @endif
            @foreach ($modify_cast_list as $modify_cast)
                <h3>{{ $loop->iteration }}件目</h3>
                <form class="modify_cast_list_form">
                    @csrf
                    <input type="submit" value="登録"
                        formaction="{{ route('modify_cast.update', ['id' => $modify_cast->id]) }}" formmethod="POST">
                    <input type="submit" value="却下"
                        formaction="{{ route('modify_cast.delete', ['id' => $modify_cast->id]) }}" formmethod="GET">
                    <table class="modify_cast_list_table">
                        <tbody>
                            <tr>
                                <th>項目</th>
                                <th>現在の情報</th>
                                <th>訂正情報</th>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td>{{ $modify_cast->cast->name }}</td>
                                <td><input type="text" name="name" value="{{ $modify_cast->name }}"></td>
                            </tr>
                            <tr>
                                <th>ふりがな</th>
                                <td>{{ $modify_cast->cast->furigana }}</td>
                                <td><input type="text" name="furigana" value="{{ $modify_cast->furigana }}"></td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $modify_cast->cast->sex_label }}</td>
                                <td>
                                    <select name="sex">
                                        <option value="" {{ is_null($modify_cast->sex) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="1" {{ $modify_cast->sex == 1 ? 'selected' : '' }}>男性
                                        </option>
                                        <option value="2" {{ $modify_cast->coor == 2 ? 'selected' : '' }}>女性
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>所属事務所</th>
                                <td>{{ $modify_cast->cast->office }}</td>
                                <td><input type="text" name="office" value="{{ $modify_cast->office }}">
                                </td>
                            </tr>
                            <tr>
                                <th>公式HP</th>
                                <td>{{ $modify_cast->cast->url }}</td>
                                <td><input type="text" name="url" value="{{ $modify_cast->url }}"></td>
                            </tr>
                            <tr>
                                <th>ツイッター</th>
                                <td>{{ '@' . $modify_cast->cast->twitter }}</td>
                                <td>@<input type="text" name="twitter" value="{{ $modify_cast->twitter }}"></td>
                            </tr>
                            <tr>
                                <th>公式ブログ</th>
                                <td>{{ $modify_cast->cast->blog }}</td>
                                <td><input type="text" name="blog" value="{{ $modify_cast->blog }}"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @endforeach
    </article>
@endsection
