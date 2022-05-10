@extends('layout')

@section('title')
    <title>データ一括入力画面 AnimeScape</title>
@endsection

@section('main')
    <article class="anime_review_list">
        <h2>データ一括入力画面</h2>
        @if (session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        <section class="search_parameters">
            <h3>検索条件変更</h3>
            <form action="{{ route('anime_review_list.show') }}" class="search_parameters_form" method="get">
                @csrf
                <select name="year" class="year">
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                    <option value="2016">2016</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                </select>年
                <select name="coor" class="coor">
                    <option value="1">冬</option>
                    <option value="2">春</option>
                    <option value="3">夏</option>
                    <option value="4">秋</option>
                </select>クールで
                <input type="submit" value="絞りこむ">
            </form>
        </section>
        <section class="anime_review_list_information">
            <h3>アニメ一覧</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('anime_review_list.post') }}" class="anime_review_list_form" method="POST">
                @csrf
                <input type="submit" value="送信">
                <table class="anime_review_list_table">
                    <tbody>
                        <tr>
                            <th>アニメ名</th>
                            <th>会社名</th>
                            <th>放送時期</th>
                            <th>得点</th>
                            <th>視聴済み</th>
                            <th>視聴予定</th>
                            <th>一言感想</th>
                            <th>ネタバレ</th>
                        </tr>
                        @foreach ($anime_list as $anime)
                            <tr>
                                <td><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                                </td>
                                <td>{{ $anime->company }}</td>
                                <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                <input type="hidden" name="year" class="year" value="{{ $anime->year }}">
                                <input type="hidden" name="coor" class="coor" value="{{ $anime->coor }}">
                                <input type="hidden" name="anime_id[{{ $loop->iteration }}]" class="anime_id"
                                    value="{{ $anime->id }}">
                                <td><input type="text" name="score[{{ $loop->iteration }}]" class="score"
                                        value="{{ $anime->userReview->score ?? '' }}"></td>
                                <input type="hidden" name="watch[{{ $loop->iteration }}]" class="watch"
                                    value="0">
                                <td><input type="checkbox" name="watch[{{ $loop->iteration }}]" class="watch"
                                        value="1" {{ $anime->userReview->watch ?? false == true ? 'checked' : '' }}></td>
                                <input type="hidden" name="will_watch[{{ $loop->iteration }}]" class="will_watch"
                                    value="0">
                                <td><input type="checkbox" name="will_watch[{{ $loop->iteration }}]"
                                        class="will_watch" value="1"
                                        {{ $anime->userReview->will_watch ?? false == true ? 'checked' : '' }}></td>
                                <td><input type="text" name="one_word_comment[{{ $loop->iteration }}]"
                                        class="one_word_comment"
                                        value="{{ $anime->userReview->one_word_comment ?? '' }}"></td>
                                <input type="hidden" name="spoiler[{{ $loop->iteration }}]" class="spoiler"
                                    value="0">
                                <td><input type="checkbox" name="spoiler[{{ $loop->iteration }}]" class="spoiler"
                                        value="1" {{ $anime->userReview->spoiler ?? false == true ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </section>
        <h3>注意事項</h3>
        得点は0～100点で付けてください。<br>
        一言感想は400文字以内でお願いします。登録は任意です。
    </article>
@endsection
