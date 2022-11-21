@extends('layout')

@section('title')
    <title>{{ $anime->title }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
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
            <p><strong>{{ $anime->title }}</strong></p>
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
                                    <th>得点数</th>
                                    <td><a
                                            href="{{ route('anime_score_list.show', ['anime_id' => $anime->id]) }}">{{ $anime->count }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>標準偏差</th>
                                    <td>{{ $anime->stdev }}</td>
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
                            @foreach ($anime->occupations as $occupation)
                                <a
                                    href="{{ route('cast.show', ['cast_id' => $occupation->cast->id]) }}">{{ $occupation->cast->name }}</a>
                                @if ($occupation->main_sub == \App\Models\Occupation::TYPE_MAIN)
                                    <b>{{ !is_null($occupation->character) ? '(' . $occupation->character . ')' : '' }}</b>
                                @elseif ($occupation->main_sub == \App\Models\Occupation::TYPE_SUB)
                                    {{ !is_null($occupation->character) ? '(' . $occupation->character . ')' : '' }}
                                @elseif ($occupation->main_sub == \App\Models\Occupation::TYPE_OTHERS)
                                    (その他)
                                @else
                                    {{ !is_null($occupation->character) ? '(' . $occupation->character . ')' : '' }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ route('modify_occupations.show', ['anime_id' => $anime->id]) }}">アニメの出演声優情報の変更をする</a>
        </section>
        <section class="creater_information">
            <h3>クリエイターの情報</h3>
            <table class="creater_information_table">
                <tbody>
                    <tr>
                        <th>監督</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_DIRECTOR) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>脚本</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_SCRIPTWRITER) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>キャラクターデザイン</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_CHARACTER_DESIGNER) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>シリーズ構成</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_SERIES_CONSTRUCTION) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>作画監督</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_ANIMATION_DIRECTOR) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>音楽</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_MUSIC) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>歌手</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_SINGER) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>原作</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_ORIGINAL_AUTHOR) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>その他</th>
                        <td>
                            @foreach ($anime->animeCreaters->where('classification', \App\Models\AnimeCreater::TYPE_CLASSIFICATION_OTHERS) as $anime_creater)
                                <a
                                    href="{{ route('creater.show', ['creater_id' => $anime_creater->creater->id]) }}">{{ $anime_creater->creater->name }}</a>
                                @if ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_MAIN)
                                    <b>{{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}</b>
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_SUB)
                                    {{ !is_null($anime_creater->occupation) ? '(' . $anime_creater->occupation . ')' : '' }}
                                @elseif ($anime_creater->main_sub == \App\Models\AnimeCreater::TYPE_OTHERS)
                                    (その他)
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ route('modify_anime_creaters.show', ['anime_id' => $anime->id]) }}">クリエイター情報の変更をする</a>
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
                        {{ $user_review->comment_timestamp }} <a
                            href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a>
                    </p>
                    <hr>
                @endif
            @endforeach
        </section>
        <section class="before_anime_information">
            <h3>視聴完了前統計情報</h3>
            <table class="before_anime_statistics_table">
                <tbody>
                    <tr>
                        <th>中央値</th>
                        <th>平均値</th>
                        <th>得点数</th>
                        <th>標準偏差</th>
                        @auth
                            <th>つけた得点</th>
                        @endauth
                    </tr>
                    <tr>
                        <td>{{ $anime->before_median }}</td>
                        <td>{{ $anime->before_average }}</td>
                        <td><a
                                href="{{ route('anime_before_score_list.show', ['anime_id' => $anime->id]) }}">{{ $anime->before_count }}</a>
                        </td>
                        <td>{{ $anime->before_stdev }}</td>
                        @auth
                            <td>{{ $anime->userReview->before_score ?? '' }}</td>
                        @endauth
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="before_anime_comment">
            <h3>視聴完了前コメント（新着順）</h3>
            @foreach ($anime->userReviews as $user_review)
                @if (!is_null($user_review->before_comment))
                    @if (!is_null($user_review->before_score))
                        <strong>{{ $user_review->before_score }}点</strong><br>
                    @endif
                    {{ $user_review->before_comment }}
                    <p>
                        {{ $user_review->before_comment_timestamp }} <a
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
                    @if (!is_null($anime->d_anime_store_id))
                        <a href="https://animestore.docomo.ne.jp/animestore/ci_pc?workId={{ $anime->d_anime_store_id }}"
                            target="_blank"
                            rel="noopener noreferrer">dアニメストア</a>{{ $anime->d_anime_store_id == 'なし' ? '(配信なし)' : '' }}
                    @else
                        dアニメストア(情報なし)
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->amazon_prime_video_id))
                        <a href="https://www.amazon.co.jp/gp/video/detail/{{ $anime->amazon_prime_video_id }}"
                            target="_blank"
                            rel="noopener noreferrer">Amazonプライムビデオ</a>{{ $anime->amazon_prime_video_id == 'なし' ? '(配信なし)' : '' }}
                    @else
                        Amazonプライムビデオ(情報なし)
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->fod_id))
                        <a href="https://fod.fujitv.co.jp/title/{{ $anime->fod_id }}" target="_blank"
                            rel="noopener noreferrer">FOD</a>{{ $anime->fod_id == 'なし' ? '(配信なし)' : '' }}
                    @else
                        FOD(情報なし)
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->unext_id))
                        <a href="https://video.unext.jp/title/{{ $anime->unext_id }}" target="_blank"
                            rel="noopener noreferrer">U-NEXT</a>{{ $anime->unext_id == 'なし' ? '(配信なし)' : '' }}
                    @else
                        U-NEXT(情報なし)
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->abema_id))
                        <a href="https://abema.tv/video/title/{{ $anime->abema_id }}" target="_blank"
                            rel="noopener noreferrer">ABEMAプレミアム</a>{{ $anime->abema_id == 'なし' ? '(配信なし)' : '' }}
                    @else
                        ABEMAプレミアム(情報なし)
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->disney_plus_id))
                        <a href="https://www.disneyplus.com/ja-jp/series/{{ $anime->disney_plus_id }}" target="_blank"
                            rel="noopener noreferrer">DISNEY+</a>{{ is_null($anime->disney_plus_id) ? '(情報なし)' : ($anime->disney_plus_id == 'なし' ? '(配信なし)' : '') }}
                    @else
                        DISNEY+(情報なし)
                    @endif
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
