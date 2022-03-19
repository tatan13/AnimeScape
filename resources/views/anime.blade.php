@extends('layout')

@section('title')
    <title>{{ $anime->title }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>
                <a href="{{ $anime->public_url }}" target="_blank" rel="noopener noreferrer">{{ $anime->title }}</a>
            </h2>
            <span><strong>{{ $anime->title }}</strong></span>
            <div class="container-fluid">
                <div class="row">
                    <div id="basic_information" class="col-md-6">
                        <table id="basic_information_table">
                            <tbody>
                                <tr id="company">
                                    <th>制作会社</th>
                                    <td>
                                        {{ $anime->company }}
                                    </td>
                                </tr>
                                <tr id="cour">
                                    <th>放送時期</th>
                                    <td>
                                        {{ $anime->year }}年{{ $anime->coor_label }}クール
                                    </td>
                                </tr>
                                <tr id="title_short">
                                    <th>略称</th>
                                    <td>
                                        {{ $anime->title_short }}
                                    </td>
                                </tr>
                                <tr id="twitter_account">
                                    <th>ツイッターアカウント</th>
                                    <td>
                                        <a href="https://twitter.com/{{ $anime->twitter }}" target="_blank" rel="noopener noreferrer">{{ $anime->twitter }}</a>
                                    </td>
                                </tr>
                                <tr id="twitter_hash_tag">
                                    <th>ツイッターハッシュタグ</th>
                                    <td>
                                        <a
                                            href="https://twitter.com/hashtag/{{ $anime->hash_tag }}" target="_blank" rel="noopener noreferrer">{{ $anime->hash_tag }}</a>
                                    </td>
                                </tr>
                                <tr id="sequel">
                                    <th>新規、続編</th>
                                    <td>
                                        {{ $anime->sequel }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="statistics_information" class="col-md-6">
                        <table id="statistics_information_table">
                            <tbody>
                                <tr id="median">
                                    <th>中央値</th>
                                    <td>{{ $anime->median }}</td>
                                </tr>
                                <tr id="average">
                                    <th>平均値</th>
                                    <td>{{ $anime->average }}</td>
                                </tr>
                                <tr id="count">
                                    <th>データ数</th>
                                    <td>{{ $anime->count }}</td>
                                </tr>
                                <tr id="max">
                                    <th>最高点</th>
                                    <td>{{ $anime->max }}</td>
                                </tr>
                                <tr id="min">
                                    <th>最低点</th>
                                    <td>{{ $anime->min }}</td>
                                </tr>
                                @if(isset($myuser_score))
                                    <tr id="myuser_score">
                                        <th>つけた得点</th>
                                        <td>{{ $myuser_score }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <a href="{{ route('modify.anime.show', ['id' => $anime->id]) }}">アニメの基本情報の変更依頼をする</a>
                    </div>
                </div>
            </div>
            <div id="toContents" class="d-grid gap-2">
                @if (Auth::check())
                    <button type="button" class="btn btn-primary" onclick="location.href='{{ route('score', ['id' => $anime->id]) }}'">このアニメに得点やコメントを登録する</button>
                @else
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('score', ['id' => $anime->id]) }}'">ログインしてこのアニメに得点やコメントを登録する</button>
                @endif
            </div>
            <div id="cast_information">
                <h3>声優の情報</h3>
                <table id="cast_information_table">
                    <tbody>
                        <tr id="casts">
                            <th>声優</th>
                            <td>
                                @foreach($anime_casts as $anime_cast)
                                    <a href="{{ route('cast', ['id' => $anime_cast->cast->id]) }}">{{ $anime_cast->cast->name }}</a>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="comment">
                <h3>コメント（新着順）</h3>
                @foreach($user_reviews as $user_review)
                    @if(!is_null($user_review->one_word_comment))
                        @if(!is_null($user_review->score))
                            <strong>{{ $user_review->score }}点</strong><br>
                        @endif
                        {{ $user_review->one_word_comment }}<br>
                        {{ $user_review->updated_at }} <a href="{{ route('user', ['uid' => $user_review->user->uid]) }}">{{ $user_review->user->uid }}</a><br>
                        <hr>
                    @endif
                @endforeach
            </div>
            <div id="twitter">
                <h2>twitter</h2>
                <div id="twitter_search">
                    <div id="twitter_search_header">
                        <h3>公式twitter</h3>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="twitter_search_main">
                                    <a class="twitter-timeline"
                                        href="https://twitter.com/{{ $anime->twitter }}?ref_src=twsrc%5Etfw">Tweets by {{ $anime->twitter }}</a>
                                    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
