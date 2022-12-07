@extends('layout')

@section('title')
    <title>{{ $anime->title }}のタグ入力 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <div id="addForm">
        <article class="tag_review">
            <h1>{{ $anime->title }}のタグ入力</h1>
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
            <form action="{{ route('tag_review.post', ['anime_id' => $anime->id]) }}" class="tag_review_form" method="POST">
                @csrf
                <input type="submit" value="送信">
                <div class="table-responsive">
                    <table class="tag_review_table">
                        <tbody>
                            <tr>
                                <th>種別</th>
                                <th>タグ名（30文字以内）</th>
                                <th>タグID</th>
                                <th>タググループ</th>
                                <th>得点（0~100）</th>
                                <th>コメント（400文字以内）</th>
                            </tr>
                            @foreach ($anime->tagReviews as $tag_review)
                                <tr>
                                    <input type="hidden" name="tag_review_id[{{ $loop->iteration }}]"
                                        value="{{ $tag_review->id }}">
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
                                    <input type="hidden" name="name[{{ $loop->iteration }}]"
                                        value="{{ $tag_review->tag->name }}">
                                    <td>{{ $tag_review->tag->name }}</td>
                                    <td>
                                        {{ $tag_review->tag->id }}
                                    </td>
                                    <td>{{ $tag_review->tag->tag_group_id_label }}</td>
                                    <td>
                                        <input type="number" name="score[{{ $loop->iteration }}]" class="score"
                                            value="{{ $tag_review->score }}">
                                    </td>
                                    <td><input type="text" size="26" name="comment[{{ $loop->iteration }}]"
                                            class="comment" value="{{ $tag_review->comment }}">
                                    </td>
                                </tr>
                            @endforeach
                            <tr v-for="(text,index) in texts">
                                <td>
                                    <select :name="'modify_type[' + (index + {{ $anime->tagReviews->count() }} + 1) + ']'">
                                        <option value="add">追加
                                        </option>
                                        <option value="no_change">変更なし
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" size="14" @change="getTagIdByName(index, $event)"
                                        :name="'name[' + (index + {{ $anime->tagReviews->count() }} + 1) + ']'">
                                </td>
                                <td>@{{ tag_id[index] }}</td>
                                <td>
                                    <select :name="'tag_group_id[' + (index + {{ $anime->tagReviews->count() }} + 1) + ']'"
                                        class="tag_group_id">
                                        <option value="{{ \App\Models\Tag::TYPE_GENRE }}">
                                            ジャンル
                                        </option>
                                        <option value="{{ \App\Models\Tag::TYPE_CHARACTER }}">
                                            キャラクター
                                        </option>
                                        <option value="{{ \App\Models\Tag::TYPE_STORY }}">
                                            ストーリー
                                        </option>
                                        <option value="{{ \App\Models\Tag::TYPE_MUSIC }}">
                                            音
                                        </option>
                                        <option value="{{ \App\Models\Tag::TYPE_PICTURE }}">
                                            作画
                                        </option>
                                        <option value="{{ \App\Models\Tag::TYPE_CAST }}">
                                            声優
                                        </option>
                                        <option value="{{ \App\Models\Tag::TYPE_OTHER }}">
                                            その他
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number"
                                        :name="'score[' + (index + {{ $anime->tagReviews->count() }} + 1) + ']'"
                                        class="score" value="">
                                </td>
                                <td><input type="text" size="26"
                                        :name="'comment[' + (index + {{ $anime->tagReviews->count() }} + 1) + ']'"
                                        class="comment" value="">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="addInput">枠の追加</button>
            </form>
            <h2>注意事項</h2>
            <ul class="list-inline">
                <li>登録されていないタグはタグIDに"登録なし"と表示され、送信すると新規登録となります。<a href="{{ route('tag_list.show') }}">タグリスト</a>を確認していただき、表記揺れがないようにお願いします。</li>
                <li>タグ名、得点は空欄のまま送信すると結果が反映されません。必ずご入力ください。</li>
            </ul>
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
                    tag_id: [],
                };
            },
            methods: {
                addInput() {
                    this.texts.push('');
                    this.tag_id.push('');
                },
                getTagIdByName(index, event) {
                    if (event.target.value == '') {
                        this.tag_id.splice(index, 1, '');
                    } else {
                        let url = `/api/tag/${event.target.value}`
                        axios.get(url)
                            .then(response => {
                                this.tag_id.splice(index, 1, response.data);
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
