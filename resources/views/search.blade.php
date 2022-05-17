@extends('layout')

@section('title')
    <title>AnimeScape</title>
@endsection

@section('main')
    <article class="search_result">
        @switch($category)
            @case('anime')
                <section class="anime_search_result">
                    <h2>検索結果 anime:{{ $search_word }}</h2>
                    @if (count($search_results) > 0)
                        <table class="anime_search_result_table">
                            <tbody>
                                <tr>
                                    <th>アニメ名</th>
                                    <th>会社名</th>
                                    <th>放送クール</th>
                                    <th>中央値</th>
                                    <th>標準偏差</th>
                                    <th>データ数</th>
                                    @auth
                                        <th>つけた得点</th>
                                    @endauth
                                </tr>
                                @foreach ($search_results as $anime)
                                    <tr>
                                        <td><a
                                                href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                        </td>
                                        <td>{{ $anime->company }}</td>
                                        <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                        <td>{{ $anime->median }}</td>
                                        <td>{{ $anime->stdev }}</td>
                                        <td>{{ $anime->count }}</td>
                                        @auth
                                            <td>{{ $anime->userReview->score ?? '' }}</td>
                                        @endauth
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        @if (isset($search_word))
                            該当するアニメがありませんでした。
                        @else
                            検索キーワードを入力してください。
                        @endif
                    @endif
                </section>
            @break

            @case('cast')
                <section class="cast_search_result">
                    <h2>検索結果 cast:{{ $search_word }}</h2>
                    @if (count($search_results) > 0)
                        @foreach ($search_results as $cast)
                            <h3><a href="{{ route('cast.show', ['cast_id' => $cast->id]) }}">{{ $cast->name }}</a></h3>
                            <strong>声優</strong>
                            <table class="cast_search_result_table">
                                <tbody>
                                    <tr>
                                        <th>アニメ名</th>
                                        <th>会社名</th>
                                        <th>放送クール</th>
                                        <th>中央値</th>
                                        <th>データ数</th>
                                        @auth
                                            <th>つけた得点</th>
                                        @endauth
                                    </tr>
                                    @foreach ($cast->actAnimes as $act_anime)
                                        <tr>
                                            <td><a
                                                    href="{{ route('anime.show', ['anime_id' => $act_anime->id]) }}">{{ $act_anime->title }}</a>
                                            </td>
                                            <td>{{ $act_anime->company }}</td>
                                            <td>
                                                {{ $act_anime->year }}年{{ $act_anime->coor_label }}クール
                                            </td>
                                            <td>{{ $act_anime->median }}</td>
                                            <td>{{ $act_anime->count }}</td>
                                            @auth
                                                <td>{{ $anime->userReview->score ?? '' }}</td>
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        @if (isset($search_word))
                            該当する声優がいませんでした。
                        @else
                            検索キーワードを入力してください。
                        @endif
                    @endif
                </section>
            @break

            @case('user')
                <section class="user_search_result">
                    <h2>検索結果 user:{{ $search_word }}</h2>
                    @if (count($search_results) > 0)
                        <ul>
                            @foreach ($search_results as $user)
                                <li>
                                    <a href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
                                </li>
                            @endforeach
                            <ul>
                            @else
                                @if (isset($search_word))
                                    該当するユーザーがいませんでした。
                                @else
                                    検索キーワードを入力してください。
                                @endif
                    @endif
                </section>
            @break
        @endswitch
    </article>
@endsection
