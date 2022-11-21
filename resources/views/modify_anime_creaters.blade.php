@extends('layout')

@section('title')
    <title>{{ $anime->title }}の出演クリエイター情報変更 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <div id="addForm">
        <article class="modify_anime_creaters">
            <h2>{{ $anime->title }}の出演クリエイター情報変更</h2>
            <h3><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <form action="{{ route('modify_anime_creaters.post', ['anime_id' => $anime->id]) }}"
                class="modify_anime_creaters_form" method="POST">
                @csrf
                <input type="submit" value="登録">
                <table class="modify_anime_creaters_table">
                    <tbody>
                        <tr>
                            <th>種別</th>
                            <th>クリエイターID</th>
                            <th>クリエイター名</th>
                            <th>職種</th>
                            <th>職種詳細</th>
                            <th>メイン/サブ</th>
                        </tr>
                        @foreach ($anime->animeCreaters as $anime_creater)
                            <tr>
                                <input type="hidden" name="anime_creater_id[{{ $loop->iteration }}]"
                                    value="{{ $anime_creater->id }}">
                                <td>
                                    <select name="modify_type[{{ $loop->iteration }}]">
                                        <option value="no_change">変更なし
                                        </option>
                                        <option value="change">変更
                                        </option>
                                        <option value="delete">削除
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="creater_id[{{ $loop->iteration }}]"
                                        value="{{ $anime_creater->creater->id }}">
                                    {{ $anime_creater->creater->id }}
                                </td>
                                <td>{{ $anime_creater->creater->name }}</td>
                                <td>
                                    <select name="classification[{{ $loop->iteration }}]">
                                        <option value=""
                                            {{ is_null($anime_creater->classification ?? null) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_DIRECTOR }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_DIRECTOR ? 'selected' : '' }}>
                                            監督
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_SCRIPTWRITER }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_SCRIPTWRITER ? 'selected' : '' }}>
                                            脚本
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_CHARACTER_DESIGNER }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_CHARACTER_DESIGNER ? 'selected' : '' }}>
                                            キャラクターデザイン
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_SERIES_CONSTRUCTION }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_SERIES_CONSTRUCTION ? 'selected' : '' }}>
                                            シリーズ構成
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_ANIMATION_DIRECTOR }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_ANIMATION_DIRECTOR ? 'selected' : '' }}>
                                            作画監督
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_MUSIC }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_MUSIC ? 'selected' : '' }}>
                                            音楽
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_SINGER }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_SINGER ? 'selected' : '' }}>
                                            歌手
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_ORIGINAL_AUTHOR }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_ORIGINAL_AUTHOR ? 'selected' : '' }}>
                                            原作
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_CLASSIFICATION_OTHERS }}"
                                            {{ $anime_creater->classification == \App\Models\AnimeCreater::TYPE_CLASSIFICATION_OTHERS ? 'selected' : '' }}>
                                            その他
                                        </option>
                                    </select>
                                </td>
                                <td><input type="text" name="occupation[{{ $loop->iteration }}]"
                                        value="{{ $anime_creater->occupation }}"></td>
                                <td>
                                    <select name="main_sub[{{ $loop->iteration }}]">
                                        <option value=""
                                            {{ is_null($anime_creater->main_sub ?? null) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_MAIN }}"
                                            {{ $anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN ? 'selected' : '' }}>
                                            メイン
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_SUB }}"
                                            {{ $anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB ? 'selected' : '' }}>
                                            サブ
                                        </option>
                                        <option value="{{ \App\Models\AnimeCreater::TYPE_OTHERS }}"
                                            {{ $anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS ? 'selected' : '' }}>
                                            その他
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                        <tr v-for="(text,index) in texts">
                            <td>
                                <select
                                    :name="'modify_type[' + (index + {{ $anime->animeCreaters->count() }} + 1) + ']'">
                                    <option value="add">追加
                                    </option>
                                    <option value="no_change">変更なし
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="number" @change="getCastName(index, $event)"
                                    :name="'creater_id[' + (index + {{ $anime->animeCreaters->count() }} + 1) + ']'">

                            </td>
                            <td>@{{ creater_name[index] }}</td>
                            <td>
                                <select
                                    :name="'classification[' + (index + {{ $anime->animeCreaters->count() }} + 1) + ']'">
                                    <option value="">-
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_DIRECTOR }}">監督
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_SCRIPTWRITER }}">脚本
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_CHARACTER_DESIGNER }}">キャラクターデザイン
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_SERIES_CONSTRUCTION }}">シリーズ構成
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_ANIMATION_DIRECTOR }}">作画監督
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_MUSIC }}">音楽
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_SINGER }}">歌手
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_ORIGINAL_AUTHOR }}">原作
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_CLASSIFICATION_OTHERS }}">その他
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                    :name="'occupation[' + (index + {{ $anime->animeCreaters->count() }} + 1) + ']'">
                            </td>
                            <td>
                                <select :name="'main_sub[' + (index + {{ $anime->animeCreaters->count() }} + 1) + ']'">
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_MAIN }}">メイン
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_SUB }}">サブ
                                    </option>
                                    <option value="{{ \App\Models\AnimeCreater::TYPE_OTHERS }}">その他
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" @click="addInput">枠の追加</button>
            </form>
            <h3>注意事項</h3>
            <a href="{{ route('creater_list.show') }}">クリエイターリスト</a>からIDを探して入力してください。<br>
            職種詳細には具体的な職種名を記入してください。<br>
            クリエイターがデータベース未登録の場合、こちらから申請してください。→<a href="{{ route('add_creater_request.show') }}">クリエイターの追加申請</a>
        </article>
    </div>
@endsection
@section('vue.js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const vue = new Vue({
            el: '#addForm',
            data() {
                return {
                    texts: [],
                    creater_name: [],
                };
            },
            methods: {
                addInput() {
                    this.texts.push('');
                    this.creater_name.push('');
                },
                getCastName(index, event) {
                    if (event.target.value == '') {
                        this.creater_name.splice(index, 1, '');
                    } else {
                        let url = `/api/creater/${event.target.value}`
                        axios.get(url)
                            .then(response => {
                                this.creater_name.splice(index, 1, response.data);
                            })
                            .catch(error => {
                                alert(error)
                            });
                    }
                },
            },
        });
    </script>
@endsection
