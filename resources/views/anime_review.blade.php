@extends('layout')

@section('title')
    <title>{{ $anime->title }}のデータ入力 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="anime_review">
        <h1>{{ $anime->title }}のデータ入力</h1>
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
        <form action="{{ route('anime_review.post', ['anime_id' => $anime->id]) }}" class="anime_review_form" method="POST">
            @csrf
            <input type="submit" value="送信">
            <div class="table-responsive">
                <table class="anime_review_table">
                    <tbody>
                        <tr>
                            <th>得点（0~100）</th>
                            <th>視聴済み</th>
                            <th>視聴予定</th>
                            <th>視聴中</th>
                            <th>視聴リタイア</th>
                            <th>視聴話数</th>
                            <th>面白さがわかる話数</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="number" name="score" class="score"
                                    value="{{ $anime->userReview->score ?? '' }}">
                            </td>
                            <td>
                                <input type="hidden" name="watch" class="watch" value="0">
                                <input type="checkbox" name="watch" class="watch" value="1" class="watch"
                                    {{ $anime->userReview->watch ?? false == true ? 'checked' : '' }}>
                            </td>
                            <td>
                                <select name="will_watch" class="will_watch">
                                    <option value="0"
                                        {{ ($anime->userReview->will_watch ?? 0) == 0 ? 'selected' : '' }}>-
                                    </option>
                                    <option value="1"
                                        {{ ($anime->userReview->will_watch ?? 0) == 1 ? 'selected' : '' }}>
                                        必ず視聴
                                    </option>
                                    <option value="2"
                                        {{ ($anime->userReview->will_watch ?? 0) == 2 ? 'selected' : '' }}>
                                        多分視聴
                                    </option>
                                    <option value="3"
                                        {{ ($anime->userReview->will_watch ?? 0) == 3 ? 'selected' : '' }}>
                                        様子見
                                    </option>
                                </select>
                            </td>
                            <td> <input type="hidden" name="now_watch" class="now_watch" value="0">
                                <input type="checkbox" name="now_watch" class="now_watch" value="1" class="now_watch"
                                    {{ $anime->userReview->now_watch ?? false == true ? 'checked' : '' }}>
                            </td>
                            <td> <input type="hidden" name="give_up" class="give_up" value="0">
                                <input type="checkbox" name="give_up" class="give_up" value="1" class="give_up"
                                    {{ $anime->userReview->give_up ?? false == true ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="number" name="number_of_watched_episode" class="number_of_watched_episode"
                                    value="{{ $anime->userReview->number_of_watched_episode ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="number_of_interesting_episode"
                                    class="number_of_interesting_episode"
                                    value="{{ $anime->userReview->number_of_interesting_episode ?? '' }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <label for="one_comment">一言感想（400文字以内、ネタバレなし）</label><br>
            <input type="text" size="100" name="one_word_comment" class="one_comment_form" style="width: 100%;"
                value="{{ $anime->userReview->one_word_comment ?? '' }}"><br>
            <hr>
            <label for="long_word_comment">長文感想（文字数制限なし）</label><br>
            <textarea name="long_word_comment" class="long_word_comment" rows="3" cols="100">{{ $anime->userReview->long_word_comment ?? '' }}</textarea><br>
            <label for="spoiler">ネタバレ？:</label>
            <input type="hidden" name="spoiler" class="spoiler" value="0">
            <input type="checkbox" name="spoiler" class="spoiler" value="1" class="spoiler"
                {{ $anime->userReview->spoiler ?? false == true ? 'checked' : '' }}><br>
            <input type="submit" value="送信"><br>
            <h2>視聴完了前入力欄</h2>
            <input type="submit" value="送信"><br>
            <label for="before_score">視聴完了前得点（0~100）</label><br>
            <input type="number" name="before_score" class="before_score"
                value="{{ $anime->userReview->before_score ?? '' }}"><br>
            <label for="before_comment">視聴完了前一言感想（400文字以内、ネタバレなし）</label><br>
            <input type="text" name="before_comment" class="before_comment" style="width: 100%;"
                value="{{ $anime->userReview->before_comment ?? '' }}">
            <label for="before_long_comment">視聴完了前長文感想（文字数制限なし）</label><br>
            <textarea name="before_long_comment" class="before_long_comment" rows="3" cols="100">{{ $anime->userReview->before_long_comment ?? '' }}</textarea><br>
            <label for="before_comment_spoiler">ネタバレ？:</label>
            <input type="hidden" name="before_comment_spoiler" class="spoiler" value="0">
            <input type="checkbox" name="before_comment_spoiler" class="spoiler" value="1" class="spoiler"
                {{ $anime->userReview->before_comment_spoiler ?? false == true ? 'checked' : '' }}><br>
        </form>
        <h2>注意事項</h2>
        <ul class="list-inline">
            <li>各欄の登録は任意です。ご自由にお使いください。</li>
            <li>長文感想に文字数制限はありません。思いの丈を思う存分書いてください。</li>
            <li>長文感想にネタバレを含む場合はネタバレ欄にチェックをお願いします。</li>
            <li>視聴完了前入力欄はまだ最終回を迎えていない今期アニメの途中評価を入力する欄です。好きなタイミングでご自由にご入力ください。</li>
        </ul>
    </article>
@endsection
