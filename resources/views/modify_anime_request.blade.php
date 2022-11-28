@extends('layout')

@section('title')
    <title>{{ $anime->title }}の基本情報変更申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="modify_anime_request">
        <h1>{{ $anime->title }}の基本情報変更申請</h1>
        <h2><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a></h2>
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
        <form action="{{ route('modify_anime_request.post', ['anime_id' => $anime->id]) }}"
            class="modify_anime_request_form" method="POST">
            @csrf
            <input type="submit" value="送信">
            <table class="modify_anime_request_table">
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
                        <th>ふりがな</th>
                        <td>{{ $anime->furigana }}</td>
                        <td><input type="text" name="furigana" value="{{ $anime->furigana }}"></td>
                    </tr>
                    <tr>
                        <th>略称</th>
                        <td>{{ $anime->title_short }}</td>
                        <td><input type="text" name="title_short" value="{{ $anime->title_short }}"></td>
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
                            <select name="coor">
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
                        <th>話数</th>
                        <td>{{ $anime->number_of_episode }}</td>
                        <td><input type="number" name="number_of_episode" value="{{ $anime->number_of_episode }}"></td>
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
                        <td>#{{ $anime->hash_tag }}</td>
                        <td>#<input type="text" name="hash_tag" value="{{ $anime->hash_tag }}"></td>
                    </tr>
                    <tr>
                        <th>舞台</th>
                        <td>{{ $anime->city_name }}</td>
                        <td><input type="text" name="city_name" value="{{ $anime->city_name }}"></td>
                    </tr>
                    <tr>
                        <th>制作会社1</th>
                        <td>{{ $anime->companies[0]->name ?? '' }}</td>
                        <td><input type="text" name="company1" value="{{ $anime->companies[0]->name ?? '' }}"></td>
                    </tr>
                    <tr>
                        <th>制作会社2</th>
                        <td>{{ $anime->companies[1]->name ?? '' }}</td>
                        <td><input type="text" name="company2" value="{{ $anime->companies[1]->name ?? '' }}"></td>
                    </tr>
                    <tr>
                        <th>制作会社3</th>
                        <td>{{ $anime->companies[2]->name ?? '' }}</td>
                        <td><input type="text" name="company3" value="{{ $anime->companies[2]->name ?? '' }}"></td>
                    </tr>
                    <tr>
                        <th>放送カテゴリー</th>
                        <td>{{ $anime->media_category_label }}</td>
                        <td>
                            <select name="media_category">
                                <option value="0" {{ $anime->media_category == 0 ? 'selected' : '' }}>-
                                </option>
                                <option value="1" {{ $anime->media_category == 1 ? 'selected' : '' }}>TVアニメ
                                </option>
                                <option value="2" {{ $anime->media_category == 2 ? 'selected' : '' }}>アニメ映画
                                </option>
                                <option value="3" {{ $anime->media_category == 3 ? 'selected' : '' }}>OVAアニメ
                                </option>
                                <option value="4" {{ $anime->media_category == 4 ? 'selected' : '' }}>配信
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>dアニメストアのID</th>
                        <td>{{ $anime->d_anime_store_id }}</td>
                        <td><input type="text" name="d_anime_store_id" value="{{ $anime->d_anime_store_id }}"></td>
                    </tr>
                    <tr>
                        <th>AmazonプライムビデオのID</th>
                        <td>{{ $anime->amazon_prime_video_id }}</td>
                        <td><input type="text" name="amazon_prime_video_id" value="{{ $anime->amazon_prime_video_id }}">
                        </td>
                    </tr>
                    <tr>
                        <th>FODのID</th>
                        <td>{{ $anime->fod_id }}</td>
                        <td><input type="text" name="fod_id" value="{{ $anime->fod_id }}"></td>
                    </tr>
                    <tr>
                        <th>U-NEXTのID</th>
                        <td></td>
                        <td><input type="text" name="unext_id" value=""></td>
                    </tr>
                    <tr>
                        <th>ABEMAプレミアムのID</th>
                        <td>{{ $anime->abema_id }}</td>
                        <td><input type="text" name="abema_id" value="{{ $anime->abema_id }}"></td>
                    </tr>
                    <tr>
                        <th>DISNEY+のID</th>
                        <td>{{ $anime->disney_plus_id }}</td>
                        <td><input type="text" name="disney_plus_id" value="{{ $anime->disney_plus_id }}"></td>
                    </tr>
                    <tr>
                        <th>あらすじ</th>
                        <td>{{ $anime->summary }}</td>
                        <td>
                            <textarea name="summary" class="summary" cols="80" rows="5">{{ $anime->summary }}</textarea><br>
                        </td>
                    </tr>
                    <tr>
                        <th>事由</th>
                        <td></td>
                        <td><input type="text" size="100" name="remark" class="remark" value="{{ old('remark') }}"></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <h2>注意事項</h2>
        <ul class="list-inline">
            <li>すべて入力する必要はありません。可能な限りでご協力お願いします。</li>
            <li>アニメ名、放送年、クール欄は空欄で送信できません。</li>
            <li>ふりがなはすべてひらがなでお願いします。</li>
            <li>略称は複数ある場合○○、○○というように「、」で区切ってください。</li>
            <li>1~3月が冬クール、4~6月が春クール、7~9月が夏クール、10月~12月が冬クールです。映画についても放映開始日をこのクールに従ってお願いします。</li>
            <li>ツイッターアカウント、ツイッターハッシュタグが存在しない場合は'なし'と書いてください。</li>
            <li>dアニメストアのIDはhttps://animestore.docomo.ne.jp/animestore/ci_pc?workId=○○の部分です。</li>
            <li>AmazonプライムビデオのIDはhttps://www.amazon.co.jp/gp/video/detail/○○の部分です。</li>
            <li>FODのIDはhttps://fod.fujitv.co.jp/title/○○の部分です。</li>
            <li>U-NEXTのIDはhttps://video.unext.jp/title/○○の部分です。</li>
            <li>ABEMAプレミアムのIDはhttps://abema.tv/video/title/○○の部分です。</li>
            <li>DISNEY+のIDはhttps://www.disneyplus.com/ja-jp/series/○○の部分です。</li>
            <li>各種配信サイトにおいて配信されていない場合、'なし'と書いてください。レンタル作品の場合は'レンタル'と書いてください。</li>
            <li>事由は400文字以内で入力してください。</li>
        </ul>
    </article>
@endsection
