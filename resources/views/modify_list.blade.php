<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/app.js"></script>
    <title>基本情報変更申請リスト AnimeScape -アニメ批評空間-</title>
</head>

<body>
    <header>
        <h1><a href="{{ route('index') }}">AnimeScape -アニメ批評空間-</a></h1><br>
    </header>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div id="modify_anime_list">
                        <h2>アニメの基本情報変更申請リスト</h2>
                        @if (session('flash_message'))
                            <div class="alert alert-success">
                                {{ session('flash_message') }}
                            </div>
                        @endif
                        @foreach ($modify_animes as $modify_anime)
                            <h3>{{ $loop->iteration }}件目</h3>
                            <form>
                                @csrf
                                <input type="submit" value="登録" formaction="{{ route('modify.anime.update',['id' => $modify_anime->id]) }}" formmethod="POST">
                                <input type="submit" value="却下" formaction="{{ route('modify.anime.delete', ['id' => $modify_anime->id]) }}" formmethod="GET">
                                <table id="modify_anime_list_table">
                                    <tbody>
                                        <tr>
                                            <th>アニメ名</th>
                                            <td><input type="text" name="title" value="{{ $modify_anime->title }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>略称</th>
                                            <td><input type="text" name="title_short"
                                                    value="{{ $modify_anime->title_short }}">
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
                                                    <option value="1"
                                                        {{ $modify_anime->coor == 1 ? 'selected' : '' }}>冬
                                                    </option>
                                                    <option value="2"
                                                        {{ $modify_anime->coor == 2 ? 'selected' : '' }}>春
                                                    </option>
                                                    <option value="3"
                                                        {{ $modify_anime->coor == 3 ? 'selected' : '' }}>夏
                                                    </option>
                                                    <option value="4"
                                                        {{ $modify_anime->coor == 4 ? 'selected' : '' }}>秋
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>公式HPのURL</th>
                                            <td><input type="text" name="public_url"
                                                    value="{{ $modify_anime->public_url }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>公式twitterID</th>
                                            <td>@<input type="text" name="twitter"
                                                    value="{{ $modify_anime->twitter }}"></td>
                                        </tr>
                                        <tr>
                                            <th>公式ハッシュタグ</th>
                                            <td><input type="text" name="hash_tag"
                                                    value="{{ $modify_anime->hash_tag }}"></td>
                                        </tr>
                                        <tr>
                                            <th>新規、続編</th>
                                            <td><input type="number" name="sequel"
                                                    value="{{ $modify_anime->sequel }}"></td>
                                        </tr>
                                        <tr>
                                            <th>制作会社</th>
                                            <td><input type="text" name="company"
                                                    value="{{ $modify_anime->company }}"></td>
                                        </tr>
                                        <tr>
                                            <th>舞台</th>
                                            <td><input type="text" name="city_name"
                                                    value="{{ $modify_anime->city_name }}"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
