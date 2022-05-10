@extends('layout')

@section('title')
    <title>{{ $anime->title }} AnimeScape</title>
@endsection

@section('main')
    <article class="anime_information">
        <h2>
            <a href="{{ $anime->public_url }}" target="_blank" rel="noopener noreferrer">{{ $anime->title }}</a>
        </h2>
        @if (session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        <section>
            <span><strong>{{ $anime->title }}</strong></span>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <table class="anime_basic_information_table">
                            <tbody>
                                <tr>
                                    <th>制作会社</th>
                                    <td>
                                        {{ $anime->company }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>放送時期</th>
                                    <td>
                                        {{ $anime->year }}年{{ $anime->coor_label }}クール
                                    </td>
                                </tr>
                                <tr>
                                    <th>ツイッターアカウント</th>
                                    <td>
                                        <a href="https://twitter.com/{{ $anime->twitter }}" target="_blank"
                                            rel="noopener noreferrer">{{ $anime->twitter }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ツイッターハッシュタグ</th>
                                    <td>
                                        <a href="https://twitter.com/hashtag/{{ $anime->hash_tag }}" target="_blank"
                                            rel="noopener noreferrer">{{ $anime->hash_tag }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="{{ route('modify_anime.show', ['id' => $anime->id]) }}">アニメの基本情報の変更依頼をする</a>
                        @can('isAdmin')
                            <br><a href="{{ route('anime.delete', ['id' => $anime->id]) }}"
                                onclick="return confirm('本当に削除しますか？')">このアニメを削除する</a>
                        @else
                        @endcan
                    </div>

                    <div class="col-md-3">
                        <table class="anime_statistics_table">
                            <tbody>
                                <tr>
                                    <th>中央値</th>
                                    <td>{{ $anime->median }}</td>
                                </tr>
                                <tr>
                                    <th>平均値</th>
                                    <td>{{ $anime->average }}</td>
                                </tr>
                                <tr>
                                    <th>データ数</th>
                                    <td>{{ $anime->count }}</td>
                                </tr>
                                <tr>
                                    <th>最高点</th>
                                    <td>{{ $anime->max }}</td>
                                </tr>
                                <tr>
                                    <th>最低点</th>
                                    <td>{{ $anime->min }}</td>
                                </tr>
                                @if (isset($my_review->score))
                                    <tr>
                                        <th>つけた得点</th>
                                        <td>{{ $my_review->score }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="toContents d-grid gap-2">
                @if (Auth::check())
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('anime_review.show', ['id' => $anime->id]) }}'">このアニメに得点やコメントを登録する</button>
                @else
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('anime_review.show', ['id' => $anime->id]) }}'">ログインしてこのアニメに得点やコメントを登録する</button>
                @endif
            </div>
        </section>
        <section class="act_cast_information">
            <h3>声優の情報</h3>
            <table class="act_cast_information_table">
                <tbody>
                    <tr>
                        <th>声優</th>
                        <td>
                            @foreach ($anime_casts as $anime_cast)
                                <a
                                    href="{{ route('cast.show', ['id' => $anime_cast->id]) }}">{{ $anime_cast->name }}</a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ route('modify_occupation.show', ['id' => $anime->id]) }}">アニメの出演声優情報の変更依頼をする</a>
        </section>
        <section class="anime_comment">
            <h3>コメント（新着順）</h3>
            @foreach ($user_reviews as $user_review)
                @if (!is_null($user_review->one_word_comment))
                    @if (!is_null($user_review->score))
                        <strong>{{ $user_review->score }}点</strong><br>
                    @endif
                    {{ $user_review->one_word_comment }}<br>
                    {{ $user_review->created_at }} <a
                        href="{{ route('user.show', ['user_name' => $user_review->user->name]) }}">{{ $user_review->user->name }}</a><br>
                    <hr>
                @endif
            @endforeach
        </section>
        <section class="anime_twitter">
            <h3>公式twitter</h3>
            <a class="twitter-timeline" href="https://twitter.com/{{ $anime->twitter }}?ref_src=twsrc%5Etfw">Tweets by
                {{ $anime->twitter }}</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </section>
    </article>
@endsection
