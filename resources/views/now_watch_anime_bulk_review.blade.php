@extends('layout')

@section('title')
    <title>視聴中アニメのデータ一括入力 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="now_watch_anime_bulk_review">
        <h1>視聴中アニメのデータ一括入力</h1>
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
        <section class="search_parameters">
            <h2>検索条件変更</h2>
            <form action="{{ route('now_watch_anime_bulk_review.show') }}" class="search_parameters_form" method="get">
                @csrf
                <select name="year">
                    @include('layout/select_year')
                </select>
                年
                <select name="coor">
                    <option value="1" {{ $coor == 1 ? 'selected' : '' }}>冬</option>
                    <option value="2" {{ $coor == 2 ? 'selected' : '' }}>春</option>
                    <option value="3" {{ $coor == 3 ? 'selected' : '' }}>夏</option>
                    <option value="4" {{ $coor == 4 ? 'selected' : '' }}>秋</option>
                </select>クールで
                <input type="submit" value="絞りこむ">
            </form>
            <a href="{{ route('now_watch_anime_bulk_review.show') }}">絞り込み解除</a>
        </section>
        <section class="now_watch_anime_bulk_review_information">
            <h2>注意事項</h2>
            <ul class="list-inline">
                <li>各欄の登録は任意です。ご自由にお使いください。</li>
                <li>得点は0～100点で付けてください。</li>
                <li>一言感想は400文字以内でネタバレなしでお願いします。</li>
            </ul>
            <h2>{{ !is_null($year) ? $year . '年' : '' }}{{ !is_null($coor) ? App\Models\Anime::getCoorLabel($coor) . 'クール' : '' }}アニメ一覧({{ $anime_list->count() }}作品)
            </h2>
            <form action="{{ route('now_watch_anime_bulk_review.show') }}" name="previous" class="d-inline" method="get">
                @csrf
                <input type="hidden" name="year" value="{{ $coor == 1 || is_null($coor) ? $year - 1 : $year }}">
                @if (!is_null($coor))
                    <input type="hidden" name="coor" value="{{ $coor == 1 ? 4 : $coor - 1 }}">
                @endif
                <a href="javascript:previous.submit()">{{ is_null($year) ? '' : (is_null($coor) ? '前の年へ' : '前クールへ') }}</a>
            </form>
            <form action="{{ route('now_watch_anime_bulk_review.show') }}" name="next" class="d-inline" method="get">
                @csrf
                <input type="hidden" name="year" value="{{ $coor == 4 || is_null($coor) ? $year + 1 : $year }}">
                @if (!is_null($coor))
                    <input type="hidden" name="coor" value="{{ $coor == 4 ? 1 : $coor + 1 }}">
                @endif
                <a href="javascript:next.submit()">{{ is_null($year) ? '' : (is_null($coor) ? '次の年へ' : '次クールへ') }}</a>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('now_watch_anime_bulk_review.post') }}" class="now_watch_anime_bulk_review_form"
                method="POST">
                @csrf
                <input type="submit" value="送信">
                <input type="hidden" name="type" value="after">
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="coor" value="{{ $coor }}">
                <div class="table-responsive">
                    <table class="now_watch_anime_bulk_review_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>得点</th>
                                <th>視聴済み</th>
                                <th>視聴予定</th>
                                <th>視聴中</th>
                                <th>視聴リタイア</th>
                                <th>視聴話数</th>
                                <th>面白さがわかる話数</th>
                                <th>一言感想</th>
                            </tr>
                            @foreach ($anime_list as $anime)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                    </td>
                                    <input type="hidden" name="anime_id[{{ $loop->iteration }}]" class="anime_id"
                                        value="{{ $anime->id }}">
                                    <td><input type="number" name="score[{{ $loop->iteration }}]" class="score"
                                            value="{{ $anime->userReview->score ?? '' }}"></td>
                                    <input type="hidden" name="watch[{{ $loop->iteration }}]" class="watch"
                                        value="0">
                                    <td><input type="checkbox" name="watch[{{ $loop->iteration }}]" class="watch"
                                            value="1"
                                            {{ $anime->userReview->watch ?? false == true ? 'checked' : '' }}>
                                    </td>
                                    <td><select name="will_watch[{{ $loop->iteration }}]" class="will_watch">
                                            <option value="0"
                                                {{ ($anime->userReview->will_watch ?? 0) == 0 ? 'selected' : '' }}>-
                                            </option>
                                            <option value="1"
                                                {{ ($anime->userReview->will_watch ?? 0) == 1 ? 'selected' : '' }}>必ず視聴
                                            </option>
                                            <option value="2"
                                                {{ ($anime->userReview->will_watch ?? 0) == 2 ? 'selected' : '' }}>多分視聴
                                            </option>
                                            <option value="3"
                                                {{ ($anime->userReview->will_watch ?? 0) == 3 ? 'selected' : '' }}>様子見
                                            </option>
                                        </select>
                                    </td>
                                    <input type="hidden" name="now_watch[{{ $loop->iteration }}]" class="now_watch"
                                        value="0">
                                    <td><input type="checkbox" name="now_watch[{{ $loop->iteration }}]" class="now_watch"
                                            value="1"
                                            {{ $anime->userReview->now_watch ?? false == true ? 'checked' : '' }}>
                                    </td>
                                    <input type="hidden" name="give_up[{{ $loop->iteration }}]" class="give_up"
                                        value="0">
                                    <td><input type="checkbox" name="give_up[{{ $loop->iteration }}]" class="give_up"
                                            value="1"
                                            {{ $anime->userReview->give_up ?? false == true ? 'checked' : '' }}>
                                    </td>
                                    <td><input type="number" name="number_of_watched_episode[{{ $loop->iteration }}]"
                                            class="number_of_watched_episode"
                                            value="{{ $anime->userReview->number_of_watched_episode ?? '' }}"></td>
                                    <td><input type="number" name="number_of_interesting_episode[{{ $loop->iteration }}]"
                                            class="number_of_interesting_episode"
                                            value="{{ $anime->userReview->number_of_interesting_episode ?? '' }}"></td>
                                    <td><input type="text" name="one_word_comment[{{ $loop->iteration }}]"
                                            size="14" class="one_word_comment"
                                            value="{{ $anime->userReview->one_word_comment ?? '' }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </section>
    </article>
@endsection