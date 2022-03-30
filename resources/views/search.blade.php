@extends('layout')

@section('title')
    <title>AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="search_result">
        @switch($category)
            @case('anime')
                <h2>検索結果 anime:{{ $search_word }}</h2>
                @if (count($search_results) > 0)
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>アニメ名</th>
                                            <th>会社名</th>
                                            <th>放送クール</th>
                                            <th>中央値</th>
                                            <th>標準偏差</th>
                                            <th>データ数</th>
                                        </tr>
                                        @foreach ($search_results as $anime)
                                            <tr>
                                                <td><a href="{{ route('anime', ['id' => $anime->id]) }}">{{ $anime->title }}</a>
                                                </td>
                                                <td>{{ $anime->company }}</td>
                                                <td>{{ $anime->year }}年{{ $anime->coor_label }}クール</td>
                                                <td>{{ $anime->median }}</td>
                                                <td>{{ $anime->stdev }}</td>
                                                <td>{{ $anime->count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    @if (isset($search_word))
                        該当するアニメがありませんでした。
                    @else
                        検索キーワードを入力してください。
                    @endif
                @endif
            @break

            @case('cast')
                <h2>検索結果 cast:{{ $search_word }}</h2>
                @if (count($search_results) > 0)
                    @foreach ($search_results as $cast)
                        <h3><a href="{{ route('cast', ['id' => $cast->id]) }}">{{ $cast->name }}</a></h3>
                        <strong>声優</strong>
                        <table>
                            <tbody>
                                <tr>
                                    <th>アニメ名</th>
                                    <th>会社名</th>
                                    <th>放送クール</th>
                                    <th>中央値</th>
                                    <th>データ数</th>
                                </tr>
                                @foreach ($cast->actAnimes as $act_anime)
                                    <tr>
                                        <td><a
                                                href="{{ route('anime', ['id' => $act_anime->id]) }}">{{ $act_anime->title }}</a>
                                        </td>
                                        <td>{{ $act_anime->company }}</td>
                                        <td>
                                            {{ $act_anime->year }}年{{ $act_anime->coor_label }}クール
                                        </td>
                                        <td>{{ $act_anime->median }}</td>
                                        <td>{{ $act_anime->count }}</td>
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
            @break

            @case('user')
                <h2>検索結果 user:{{ $search_word }}</h2>
                @if (count($search_results) > 0)
                    <ul>
                        @foreach ($search_results as $user)
                            <li>
                                <a href="{{ route('user', ['uid' => $user->uid]) }}">{{ $user->uid }}</a>
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
            @break

            @default
                <h2>検索結果</h2>
                不正なアクセスです。
        @endswitch
    </div>
    </div>
    </div>
@endsection
