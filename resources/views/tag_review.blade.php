@extends('layout')

@section('title')
    <title>{{ $tag->name }}のアニメへの一括入力 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <div id="addForm">
        <article class="tag_review">
            <h1>{{ $tag->name }}のアニメへの一括入力</h1>
            <h2><a href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a></h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('tag_review.post', ['tag_id' => $tag->id]) }}" class="tag_review_form" method="POST">
                @csrf
                <input type="submit" value="送信">
                <div class="table-responsive">
                    <table class="tag_review_table">
                        <tbody>
                            <tr>
                                <th>種別</th>
                                <th>アニメID</th>
                                <th>アニメ名</th>
                                <th>合致度（0~100）</th>
                                <th>コメント（400文字以内）</th>
                            </tr>
                            @foreach ($tag->tagReviews as $tag_review)
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
                                    <input type="hidden" name="anime_id[{{ $loop->iteration }}]"
                                        value="{{ $tag_review->anime->id }}">
                                    <td>
                                        {{ $tag_review->anime->id }}
                                    </td>
                                    <td>{{ $tag_review->anime->title }}</td>
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
                                    <select :name="'modify_type[' + (index + {{ $tag->tagReviews->count() }} + 1) + ']'">
                                        <option value="add">追加
                                        </option>
                                        <option value="no_change">変更なし
                                        </option>
                                    </select>
                                </td>
                                <td> <input type="number" @change="getAnimeTitleById(index, $event)"
                                        :name="'anime_id[' + (index + {{ $tag->tagReviews->count() }} + 1) + ']'"></td>
                                <td>@{{ animeTitle[index] }}</td>
                                <td>
                                    <input type="number"
                                        :name="'score[' + (index + {{ $tag->tagReviews->count() }} + 1) + ']'"
                                        class="score" value="">
                                </td>
                                <td><input type="text" size="26"
                                        :name="'comment[' + (index + {{ $tag->tagReviews->count() }} + 1) + ']'"
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
                <li><a href="{{ route('anime_list.show') }}">アニメリスト</a>からIDを探して入力してください。</li>
                <li>アニメ名、合致度は空欄のまま送信すると結果が反映されません。</li>
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
                    animeTitle: [],
                };
            },
            methods: {
                addInput() {
                    this.texts.push('');
                    this.animeTitle.push('');
                },
                getAnimeTitleById(index, event) {
                    if (event.target.value == '') {
                        this.animeTitle.splice(index, 1, '');
                    } else {
                        let url = `/api/anime/id/${event.target.value}`
                        axios.get(url)
                            .then(response => {
                                this.animeTitle.splice(index, 1, response.data);
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
