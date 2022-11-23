@extends('layout')

@section('title')
    <title>{{ $anime->title }}の出演声優情報変更 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <div id="addForm">
        <article class="modify_occupations">
            <h2>{{ $anime->title }}の出演声優情報変更</h2>
            <h3><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <form action="{{ route('modify_occupations.post', ['anime_id' => $anime->id]) }}"
                class="modify_occupations_form" method="POST">
                @csrf
                <input type="submit" value="登録">
                <table class="modify_occupations_table">
                    <tbody>
                        <tr>
                            <th>種別</th>
                            <th>声優ID</th>
                            <th>声優名</th>
                            <th>キャラクター名</th>
                            <th>メイン/サブ</th>
                        </tr>
                        @foreach ($anime->occupations as $occupation)
                            <tr>
                                <input type="hidden" name="occupation_id[{{ $loop->iteration }}]"
                                    value="{{ $occupation->id }}">
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
                                    <input type="hidden" name="cast_id[{{ $loop->iteration }}]"
                                        value="{{ $occupation->cast->id }}">
                                    {{ $occupation->cast->id }}
                                </td>
                                <td>{{ $occupation->cast->name }}</td>
                                <td><input type="text" name="character[{{ $loop->iteration }}]"
                                        value="{{ $occupation->character }}"></td>
                                <td>
                                    <select name="main_sub[{{ $loop->iteration }}]">
                                        <option value="" {{ is_null($occupation->main_sub ?? null) ? 'selected' : '' }}>-
                                        </option>
                                        <option value="{{ \App\Models\Occupation::TYPE_MAIN }}" {{ $occupation->main_sub == \App\Models\Occupation::TYPE_MAIN ? 'selected' : '' }}>メイン
                                        </option>
                                        <option value="{{ \App\Models\Occupation::TYPE_SUB }}" {{ $occupation->main_sub == \App\Models\Occupation::TYPE_SUB ? 'selected' : '' }}>サブ
                                        </option>
                                        <option value="{{ \App\Models\Occupation::TYPE_OTHERS }}" {{ $occupation->main_sub == \App\Models\Occupation::TYPE_OTHERS ? 'selected' : '' }}>その他
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                        <tr v-for="(text,index) in texts">
                            <td>
                                <select :name="'modify_type[' + (index + {{ $anime->occupations->count() }} + 1) + ']'">
                                    <option value="add">追加
                                    </option>
                                    <option value="no_change">変更なし
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="number" @change="getCastName(index, $event)"
                                    :name="'cast_id[' + (index + {{ $anime->occupations->count() }} + 1) + ']'">

                            </td>
                            <td>@{{ cast_name[index] }}</td>
                            <td>
                                <input type="text"
                                    :name="'character[' + (index + {{ $anime->occupations->count() }} + 1) + ']'">
                            </td>
                            <td>
                                <select :name="'main_sub[' + (index + {{ $anime->occupations->count() }} + 1) + ']'">
                                    <option value="{{ \App\Models\Occupation::TYPE_MAIN }}">メイン
                                    </option>
                                    <option value="{{ \App\Models\Occupation::TYPE_SUB }}">サブ
                                    </option>
                                    <option value="{{ \App\Models\Occupation::TYPE_OTHERS }}">その他
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" @click="addInput">枠の追加</button>
            </form>
            <h3>注意事項</h3>
            <a href="{{ route('cast_list.show') }}">声優リスト</a>からIDを探して入力してください。<br>
            キャラクターを複数演じている場合は、A、Bのように入力してください。<br>
            声優がデータベース未登録の場合、こちらから申請をお願いします→<a href="{{ route('add_cast_request.show') }}">声優の追加申請</a>　申請許可まで時間がかかりますがご了承ください。
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
                    cast_name: [],
                };
            },
            methods: {
                addInput() {
                    this.texts.push('');
                    this.cast_name.push('');
                },
                getCastName(index, event) {
                    if (event.target.value == '') {
                        this.cast_name.splice(index, 1, '');
                    } else {
                        let url = `/api/cast/${event.target.value}`
                        axios.get(url)
                            .then(response => {
                                this.cast_name.splice(index, 1, response.data);
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
