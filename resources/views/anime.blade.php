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
                                        @foreach ($anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>放送時期</th>
                                    <td>
                                        {{ $anime->year }}年{{ $anime->coor_label }}クール
                                    </td>
                                </tr>
                                <tr>
                                    <th>略称</th>
                                    <td>
                                        {{ $anime->title_short }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>話数</th>
                                    <td>
                                        {{ $anime->number_of_episode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>放送カテゴリー</th>
                                    <td>
                                        {{ $anime->media_category_label }}
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
                                <tr>
                                    <th>舞台</th>
                                    <td>
                                        {{ $anime->city_name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <a
                            href="{{ route('modify_anime_request.show', ['anime_id' => $anime->id]) }}">アニメの基本情報の変更申請をする</a><br>
                        <a href="{{ route('delete_anime_request.show', ['anime_id' => $anime->id]) }}">アニメの削除申請をする</a>
                        @can('isAdmin')
                            <br><a href="{{ route('anime.delete', ['anime_id' => $anime->id]) }}"
                                onclick="return confirm('本当に削除しますか？')">このアニメを削除する</a>
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
                                <tr>
                                    <th>面白さがわかる話数</th>
                                    <td>{{ $anime->number_of_interesting_episode }}</td>
                                </tr>
                                @auth
                                    <tr>
                                        <th>つけた得点</th>
                                        <td>{{ $anime->userReview->score ?? '' }}</td>
                                    </tr>
                                @endauth
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="toContents d-grid gap-2">
                @if (Auth::check())
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('anime_review.show', ['anime_id' => $anime->id]) }}'">このアニメに得点やコメントを登録する</button>
                @else
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('anime_review.show', ['anime_id' => $anime->id]) }}'">ログインしてこのアニメに得点やコメントを登録する</button>
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
                            @foreach ($anime->actCasts as $actCast)
                                <a
                                    href="{{ route('cast.show', ['cast_id' => $actCast->id]) }}">{{ $actCast->name }}</a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ route('modify_occupations_request.show', ['anime_id' => $anime->id]) }}">アニメの出演声優情報の変更申請をする</a>
        </section>
        <section class="anime_comment">
            <h3>コメント（新着順）</h3>
            @foreach ($anime->userReviews as $user_review)
                @if (!is_null($user_review->one_word_comment) || !is_null($user_review->long_word_comment))
                    @if (!is_null($user_review->score))
                        <strong>{{ $user_review->score }}点</strong><br>
                    @endif
                    {{ $user_review->one_word_comment }}
                    @if (!is_null($user_review->long_word_comment))
                        <a href="{{ route('user_anime_comment.show', ['user_review_id' => $user_review->id]) }}">→長文感想({{ mb_strlen($user_review->long_word_comment) }}文字)
                            @if ($user_review->spoiler == true)
                                (ネタバレ注意)
                            @endif
                        </a>
                    @endif
                    <p>
                        {{ $user_review->created_at }} <a
                            href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
                    </p>
                    <hr>
                @endif
            @endforeach
        </section>
        <section class="streaming_information">
            <h3>配信サイトのリンク</h3>
            <ul>
                <li>
                    <a href="https://animestore.docomo.ne.jp/animestore/ci_pc?workId={{ $anime->d_anime_store_id }}"
                        target="_blank"
                        rel="noopener noreferrer">dアニメストア</a>{{ is_null($anime->d_anime_store_id) ? '(情報なし)' : ($anime->d_anime_store_id == 'なし' ? '(配信なし)' : '') }}
                </li>
                <li>
                    <a href="https://www.amazon.co.jp/gp/video/detail/{{ $anime->amazon_prime_video_id }}"
                        target="_blank"
                        rel="noopener noreferrer">Amazonプライムビデオ</a>{{ is_null($anime->amazon_prime_video_id) ? '(情報なし)' : ($anime->amazon_prime_video_id == 'なし' ? '(配信なし)' : '') }}
                </li>
                <li>
                    <a href="https://fod.fujitv.co.jp/title/{{ $anime->fod_id }}" target="_blank"
                        rel="noopener noreferrer">FOD</a>{{ is_null($anime->fod_id) ? '(情報なし)' : ($anime->fod_id == 'なし' ? '(配信なし)' : '') }}
                </li>
                <li>
                    <a href="https://video.unext.jp/title/{{ $anime->unext_id }}" target="_blank"
                        rel="noopener noreferrer">U-NEXT</a>{{ is_null($anime->unext_id) ? '(情報なし)' : ($anime->unext_id == 'なし' ? '(配信なし)' : '') }}
                </li>
                <li>
                    <a href="https://abema.tv/video/title/{{ $anime->abema_id }}" target="_blank"
                        rel="noopener noreferrer">ABEMAプレミアム</a>{{ is_null($anime->abema_id) ? '(情報なし)' : ($anime->abema_id == 'なし' ? '(配信なし)' : '') }}
                </li>
                <li>
                    <a href="https://www.disneyplus.com/ja-jp/series/{{ $anime->disney_plus_id }}" target="_blank"
                        rel="noopener noreferrer">DISNEY+</a>{{ is_null($anime->disney_plus_id) ? '(情報なし)' : ($anime->disney_plus_id == 'なし' ? '(配信なし)' : '') }}
                </li>
            </ul>
        </section>
        <section class="anime_twitter">
            <h3>公式twitter</h3>
            <div class="anime_twitter" style="width: 50%;">
                <a class="twitter-timeline" href="https://twitter.com/{{ $anime->twitter }}?ref_src=twsrc%5Etfw">Tweets
                    by
                    {{ $anime->twitter }}</a>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </section>
    </article>
@endsection
