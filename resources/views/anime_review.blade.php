@extends('layout')

@section('title')
    <title>{{ $anime->title }}のデータ入力画面 AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('title_adsense')
    @include('layout.horizontal_adsense')
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <article class="anime_review">
        <h2>{{ $anime->title }}のデータ入力画面</h2>
        <h3><a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
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
            <table class="anime_review_table">
                <tbody>
                    <tr>
                        <th>得点</th>
                        <th>視聴済み</th>
                        <th>視聴予定</th>
                        <th>視聴中</th>
                        <th>視聴リタイア</th>
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
                                <option value="0" {{ ($anime->userReview->will_watch ?? 0) == 0 ? 'selected' : '' }}>-
                                </option>
                                <option value="1" {{ ($anime->userReview->will_watch ?? 0) == 1 ? 'selected' : '' }}>
                                    必ず視聴
                                </option>
                                <option value="2" {{ ($anime->userReview->will_watch ?? 0) == 2 ? 'selected' : '' }}>
                                    多分視聴
                                </option>
                                <option value="3" {{ ($anime->userReview->will_watch ?? 0) == 3 ? 'selected' : '' }}>
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
                            <input type="number" name="number_of_interesting_episode" class="number_of_interesting_episode"
                                value="{{ $anime->userReview->number_of_interesting_episode ?? '' }}">
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <label for="one_comment">一言感想</label><br>
            <input type="text" size="100" name="one_word_comment" class="one_comment_form"
                value="{{ $anime->userReview->one_word_comment ?? '' }}"><br>
            <hr>
            <label for="long_word_comment">長文感想</label><br>
            <textarea name="long_word_comment" class="long_word_comment" cols="80" rows="5">{{ $anime->userReview->long_word_comment ?? '' }}</textarea><br>
            <label for="netabare_check">ネタバレ？:</label>
            <input type="hidden" name="spoiler" class="spoiler" value="0">
            <input type="checkbox" name="spoiler" class="spoiler" value="1" class="spoiler"
                {{ $anime->userReview->spoiler ?? false == true ? 'checked' : '' }}><br>
            <input type="submit" value="送信"><br>
            <h3>視聴完了前入力欄</h3>
            <input type="submit" value="送信">
            <table class="before_anime_review_table">
                <tbody>
                    <tr>
                        <th>視聴完了前得点</th>
                        <th>視聴完了前一言感想</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" name="before_score" class="before_score"
                                value="{{ $anime->userReview->before_score ?? '' }}">
                        </td>
                        <td><input type="text" size="100" name="before_comment" class="before_comment"
                                value="{{ $anime->userReview->before_comment ?? '' }}">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <h3>注意事項</h3>
        <ul class="list-inline">
            <li>得点は0～100点で付けてください。</li>
            <li>一言感想は400文字以内でお願いします。登録は任意です。</li>
        </ul>
    </article>
@endsection
