@extends('layout')

@section('title')
    <title>基本情報変更申請リスト AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="modify_anime_list">
        <h2>アニメの基本情報変更申請リスト</h2>
        @if (session('flash_anime_message'))
            <div class="alert alert-success">
                {{ session('flash_anime_message') }}
            </div>
        @endif
        @foreach ($modify_animes as $modify_anime)
            <h3>{{ $loop->iteration }}件目</h3>
            <form>
                @csrf
                <input type="submit" value="登録"
                    formaction="{{ route('modify.anime.update', ['id' => $modify_anime->id]) }}" formmethod="POST">
                <input type="submit" value="却下"
                    formaction="{{ route('modify.anime.delete', ['id' => $modify_anime->id]) }}" formmethod="GET">
                <table id="modify_anime_list_table">
                    <tbody>
                        <tr>
                            <th>アニメ名</th>
                            <td><input type="text" name="title" value="{{ $modify_anime->title }}">
                            </td>
                        </tr>
                        <tr>
                            <th>略称</th>
                            <td><input type="text" name="title_short" value="{{ $modify_anime->title_short }}">
                            </td>
                        </tr>
                        <tr>
                            <th>放送年</th>
                            <td><input type="number" name="year" value="{{ $modify_anime->year }}">
                            </td>
                        </tr>
                        <tr>
                            <th>クール</th>
                            <td>
                                <select name="coor" id="coor">
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
                            <td><input type="text" name="public_url" value="{{ $modify_anime->public_url }}">
                            </td>
                        </tr>
                        <tr>
                            <th>公式twitterID</th>
                            <td>@<input type="text" name="twitter" value="{{ $modify_anime->twitter }}"></td>
                        </tr>
                        <tr>
                            <th>公式ハッシュタグ</th>
                            <td><input type="text" name="hash_tag" value="{{ $modify_anime->hash_tag }}"></td>
                        </tr>
                        <tr>
                            <th>新規、続編</th>
                            <td><input type="number" name="sequel" value="{{ $modify_anime->sequel }}"></td>
                        </tr>
                        <tr>
                            <th>制作会社</th>
                            <td><input type="text" name="company" value="{{ $modify_anime->company }}"></td>
                        </tr>
                        <tr>
                            <th>舞台</th>
                            <td><input type="text" name="city_name" value="{{ $modify_anime->city_name }}"></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        @endforeach
    </div>
    <div id="modify_occupation_list">
        <h2>アニメの出演声優情報変更申請リスト</h2>
        @if (session('flash_occupation_message'))
            <div class="alert alert-success">
                {{ session('flash_occupation_message') }}
            </div>
        @endif
        @foreach ($modify_occupations_list as $modify_occupation_list)
            <h3>{{ $loop->iteration }}件目({{ $modify_occupation_list[0]->anime->title }})</h3>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <strong>現在の情報</strong>
                        <table id="occupation_list_table">
                            <tbody>
                                <th>声優名</th>
                                @foreach ($modify_occupation_list[0]->anime->occupations as $occupation)
                                    <tr>
                                        <td>{{ $occupation->cast->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2">
                        <form>
                            @csrf
                            <strong>変更依頼情報</strong>
                            <table id="modify_occupation_list_table">
                                <tbody>
                                    <th>声優名</th>
                                    @foreach ($modify_occupation_list as $modify_occupation)
                                        <tr>
                                            <td><input type="text" name="cast_name_{{ $loop->iteration }}"
                                                    value="{{ $modify_occupation->cast_name }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="submit" value="登録" formaction="{{ route('modify.occupation.update', ['id' => $modify_occupation_list[0]->anime->id]) }}"
                                formmethod="POST">
                            <input type="submit" value="却下" formaction="{{ route('modify.occupation.delete', ['id' => $modify_occupation_list[0]->anime->id]) }}"
                                formmethod="GET">
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>
    </div>
@endsection
