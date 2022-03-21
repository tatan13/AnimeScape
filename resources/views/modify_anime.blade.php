<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/app.js"></script>
    <title>{{ $anime->title }}の基本情報変更申請 AnimeScape -アニメ批評空間-</title>
</head>

<body>
    <header>
        <h1><a href="{{ route('index') }}">AnimeScape -アニメ批評空間-</a></h1><br>
    </header>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div id="modify_anime">
                        <h2>{{ $anime->title }}の基本情報変更申請</h2>
                        <h3><a href="{{ route('anime', ['id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
                        @if (session('flash_message'))
                            <div class="alert alert-success">
                                {{ session('flash_message') }}
                            </div>
                        @endif
                        <form action="{{ route('modify.anime.post', ['id' => $anime->id]) }}"  method="POST">
                            @csrf
                            <input type="submit" value="登録">
                            <table id="modify_anime_table">
                                <tbody>
                                    <tr>
                                        <th>項目</th>
                                        <th>現在の情報</th>
                                        <th>訂正情報</th>
                                    </tr>
                                    <tr>
                                        <th>アニメ名</th>
                                        <td>{{ $anime->title }}</td>
                                        <td><input type="text" name="title" value="{{ $anime->title }}"></td>
                                    </tr>
                                    <tr>
                                        <th>略称</th>
                                        <td>{{ $anime->title_short }}</td>
                                        <td><input type="text" name="title_short" value="{{ $anime->title_short }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>放送年</th>
                                        <td>{{ $anime->year }}</td>
                                        <td><input type="number" name="year" value="{{ $anime->year }}"></td>
                                    </tr>
                                    <tr>
                                        <th>クール</th>
                                        <td>{{ $anime->coor_label }}</td>
                                        <td>
                                            <select name="coor" id="coor">
                                                <option value="1" {{ $anime->coor == 1 ? 'selected' : '' }}>冬
                                                </option>
                                                <option value="2" {{ $anime->coor == 2 ? 'selected' : '' }}>春
                                                </option>
                                                <option value="3" {{ $anime->coor == 3 ? 'selected' : '' }}>夏
                                                </option>
                                                <option value="4" {{ $anime->coor == 4 ? 'selected' : '' }}>秋
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>公式HPのURL</th>
                                        <td>{{ $anime->public_url }}</td>
                                        <td><input type="text" name="public_url" value="{{ $anime->public_url }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>公式twitterID</th>
                                        <td>{{ '@' . $anime->twitter }}</td>
                                        <td>@<input type="text" name="twitter" value="{{ $anime->twitter }}"></td>
                                    </tr>
                                    <tr>
                                        <th>公式ハッシュタグ</th>
                                        <td>{{ $anime->hash_tag }}</td>
                                        <td><input type="text" name="hash_tag" value="{{ $anime->hash_tag }}"></td>
                                    </tr>
                                    <tr>
                                        <th>新規、続編</th>
                                        <td>{{ $anime->sequel }}</td>
                                        <td><input type="number" name="sequel" value="{{ $anime->sequel }}"></td>
                                    </tr>
                                    <tr>
                                        <th>制作会社</th>
                                        <td>{{ $anime->company }}</td>
                                        <td><input type="text" name="company" value="{{ $anime->company }}"></td>
                                    </tr>
                                    <tr>
                                        <th>舞台</th>
                                        <td>{{ $anime->city_name }}</td>
                                        <td><input type="text" name="city_name" value="{{ $anime->city_name }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
