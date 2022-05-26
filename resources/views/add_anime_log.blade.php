@extends('layout')

@section('title')
    <title>作品の追加履歴 AnimeScape</title>
@endsection

@section('main')
    <article class="add_anime_log">
        <h2>アニメの追加履歴</h2>
        @if (session('flash_add_anime_log_message'))
            <div class="alert alert-success">
                {{ session('flash_add_anime_log_message') }}
            </div>
        @endif
        @foreach ($add_anime_list as $add_anime)
            <h3>{{ $add_anime->updated_at }}</h3>
                <table class="add_anime_log_table">
                    <tbody>
                        <tr>
                            <th>項目</th>
                            <th>情報</th>
                        </tr>
                        <tr>
                            <th>アニメ名</th>
                            <td>{{ $add_anime->title }}</td>
                        </tr>
                        <tr>
                            <th>ふりがな</th>
                            <td>{{ $add_anime->furigana }}</td>
                        </tr>
                        <tr>
                            <th>略称</th>
                            <td>{{ $add_anime->title_short }}</td>
                        </tr>
                        <tr>
                            <th>放送年</th>
                            <td>{{ $add_anime->year }}</td>
                        </tr>
                        <tr>
                            <th>クール</th>
                            <td>
                                {{ $add_anime->coor_label}}
                            </td>
                        </tr>
                        <tr>
                            <th>話数</th>
                            <td>{{ $add_anime->number_of_episode }}</td>
                        </tr>
                        <tr>
                            <th>公式HPのURL</th>
                            <td>{{ $add_anime->public_url }}</td>
                        </tr>
                        <tr>
                            <th>公式twitterID</th>
                            <td>{{'@' . $add_anime->twitter }}</td>
                        </tr>
                        <tr>
                            <th>公式ハッシュタグ</th>
                            <td>{{ $add_anime->hash_tag }}</td>
                        </tr>
                        <tr>
                            <th>舞台</th>
                            <td>{{ $add_anime->city_name }}</td>
                        </tr>
                        <tr>
                            <th>制作会社1</th>
                            <td>{{ $add_anime->company1 }}</td>
                        </tr>
                        <tr>
                            <th>制作会社2</th>
                            <td>{{ $add_anime->company2 }}</td>
                        </tr>
                        <tr>
                            <th>制作会社3</th>
                            <td>{{ $add_anime->company3 }}</td>
                        </tr>
                        <tr>
                            <th>dアニメストアのID</th>
                            <td>{{ $add_anime->d_anime_store_id }}</td>
                        </tr>
                        <tr>
                            <th>AmazonプライムビデオのID</th>
                            <td>{{ $add_anime->amazon_prime_video_id }}</td>
                        </tr>
                        <tr>
                            <th>FODのID</th>
                            <td>{{ $add_anime->fod_id }}</td>
                        </tr>
                        <tr>
                            <th>U-NEXTのID</th>
                            <td>{{ $add_anime->unext_id }}</td>
                        </tr>
                        <tr>
                            <th>ABEMAプレミアムのID</th>
                            <td>{{ $add_anime->abema_id }}</td>
                        </tr>
                        <tr>
                            <th>DISNEY+のID</th>
                            <td>{{ $add_anime->disney_plus_id }}</td>
                        </tr>
                        <tr>
                            <th>あらすじ</th>
                            <td>{{ $add_anime->summary }}<br>
                            </td>
                        </tr>
                    </tbody>
                </table>
        @endforeach
    </article>
@endsection
