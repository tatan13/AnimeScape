@extends('layout')

@section('title')
    <title>アニメの追加申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
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
            <input type="submit" value="送信">
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
                        <td>#<input type="text" name="hash_tag" value="{{ old('hash_tag') }}"></td>
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
                        <th>放送カテゴリー</th>
                        <td>
                            <select name="media_category">
                                <option value="0" {{ old('media_category') == 0 ? 'selected' : '' }}>-
                                </option>
                                <option value="1" {{ old('media_category') == 1 ? 'selected' : '' }}>TVアニメ
                                </option>
                                <option value="2" {{ old('media_category') == 2 ? 'selected' : '' }}>アニメ映画
                                </option>
                                <option value="3" {{ old('media_category') == 3 ? 'selected' : '' }}>OVAアニメ
                                </option>
                                <option value="4" {{ old('media_category') == 4 ? 'selected' : '' }}>配信
                                </option>
                            </select>
                        </td>
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
                            <textarea name="summary" class="summary" cols="80" rows="5">{{ old('summary') }}</textarea><br>
                        </td>
                    </tr>
                    <tr>
                        <th>事由</th>
                        <td><input type="text" size="100" name="remark" class="remark" value="{{ old('remark') }}">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <h3>注意事項</h3>
        <ul class="list-inline">
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
