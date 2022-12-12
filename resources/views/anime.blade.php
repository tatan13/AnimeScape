@extends('layout')

@section('title')
    <title>
        {{ $anime->title }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection


@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@if (env('APP_ENV') == 'production')
    @section('main_adsense_smartphone')
        @include('layout.horizontal_adsense_smartphone')
    @endsection
@endif

@section('main')
    <article class="anime_information">
        <h1>
            <a href="{{ $anime->public_url }}" target="_blank" rel="noopener noreferrer">{{ $anime->title }}</a>
        </h1>
        @if (session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        <section class="anime_information">
            <div class="title">{{ $anime->title }}</div>
            <div class="d-flex flex-wrap justify-content-between">
                <div>
                    <div class="table-responsive">
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
                                    <td><a
                                            href="{{ route('anime_statistics.show', ['year' => $anime->year, 'coor' => $anime->coor]) }}">
                                            {{ $anime->year }}年{{ $anime->coor_label }}クール</a>
                                    </td>
                                </tr>
                                @if (!is_null($anime->title_short))
                                    <tr>
                                        <th>略称</th>
                                        <td>
                                            {{ $anime->title_short }}
                                        </td>
                                    </tr>
                                @endif
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
                                        @if (!is_null($anime->twitter) && $anime->twitter != 'なし')
                                            <a href="https://twitter.com/{{ $anime->twitter }}" target="_blank"
                                                rel="noopener noreferrer">{{ $anime->twitter }}</a>
                                        @else
                                            {{ $anime->twitter == 'なし' ? '(なし)' : '' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ツイッターハッシュタグ</th>
                                    <td>
                                        @if (!is_null($anime->hash_tag) && $anime->hash_tag != 'なし')
                                            <a href="https://twitter.com/hashtag/{{ $anime->hash_tag }}" target="_blank"
                                                rel="noopener noreferrer">{{ $anime->hash_tag }}</a>
                                        @else
                                            {{ $anime->hash_tag == 'なし' ? '(なし)' : '' }}
                                        @endif
                                    </td>
                                </tr>
                                @if (!is_null($anime->city_name))
                                    <tr>
                                        <th>舞台</th>
                                        <td>
                                            {{ $anime->city_name }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('modify_anime_request.show', ['anime_id' => $anime->id]) }}">アニメの基本情報の変更申請をする</a><br>
                    <a href="{{ route('delete_anime_request.show', ['anime_id' => $anime->id]) }}">アニメの削除申請をする</a>
                    @can('isAdmin')
                        <br><a href="{{ route('anime.delete', ['anime_id' => $anime->id]) }}"
                            onclick="return confirm('本当に削除しますか？')">このアニメを削除する</a>
                    @endcan
                </div>
                <div>
                    <div class="table-responsive">
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
                <div></div>
                <div></div>
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
        @if (Auth::check() && $like_users->count() != 0)
            <section class="watch_like_users_review">
                <h2>視聴済みお気に入りユーザーのレビュー</h2>
                <div class="table-responsive">
                    <table class="watch_like_users_review_table">
                        <tbody>
                            <tr>
                                <th>ユーザー名</th>
                                <th>得点</th>
                                <th>コメント</th>
                            </tr>
                            @foreach ($like_users as $like_user)
                                <tr>
                                    <td><a
                                            href="{{ route('user.show', ['user_id' => $like_user->id]) }}">{{ $like_user->name }}</a>
                                    </td>
                                    <td>{{ $like_user->userReview->score }}</td>
                                    <td>{{ is_null($like_user->one_word_comment) ? '' : 'あり' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
        <section class="anime_summary">
            <h2>あらすじ</h2>
            {{ $anime->summary ?? '(あらすじ情報がありません。情報提供してくださると助かります。)' }}
        </section>
        <section class="act_cast_information">
            <h2>声優の情報</h2>
            <div class="table-responsive">
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
            </div>
            <a href="{{ route('modify_occupations.show', ['anime_id' => $anime->id]) }}">アニメの出演声優情報の変更をする</a>
        </section>
        <section class="creater_information">
            <h2>クリエイターの情報</h2>
            <div class="table-responsive">
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
            </div>
            <a href="{{ route('modify_anime_creaters.show', ['anime_id' => $anime->id]) }}">クリエイター情報の変更をする</a>
        </section>
        <section class="tag_information">
            <h2>タグ情報</h2>
            <div class="table-responsive">
                <table class="tag_information_table">
                    <tbody>
                        <tr>
                            <th>ジャンル</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_GENRE) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>キャラクター</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_CHARACTER) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>ストーリー</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_STORY) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>音</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_MUSIC) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>作画</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_PICTURE) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>声優</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_CAST) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>その他</th>
                            <td>
                                @foreach ($tags->where('tag_group_id', \App\Models\Tag::TYPE_OTHER) as $tag)
                                    <a
                                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }})
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="toContents d-grid gap-2">
                @if (Auth::check())
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('tag_review.show', ['anime_id' => $anime->id]) }}'">タグ情報の登録をする</button>
                @else
                    <button type="button" class="btn btn-primary"
                        onclick="location.href='{{ route('tag_review.show', ['anime_id' => $anime->id]) }}'">ログインしてタグ情報の登録をする</button>
                @endif
            </div>
        </section>
        <section class="top_tag_list">
            <h2>タグ詳細</h2>
            @foreach ($tags as $tag)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
                <a href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a><br>
                {{ $tag->tag_reviews_count }}件 中央値{{ $tag->tagReviews->median('score') }}点
                </div>
            @endforeach
        </section>
        <section class="top_tag_comment_list">
            <h2>タグコメント</h2>
            @foreach ($tags as $tag)
                @if ($tag->tagReviews->whereNotNull('comment')->count() != 0)
                    <a
                        href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a>({{ $tag->tag_reviews_count }}件、中央値{{ $tag->tagReviews->median('score') }}点)<br>
                    @foreach ($tag->tagReviews->whereNotNull('comment') as $tag_review)
                        @if ($loop->iteration % 2 == 0)
                            <div class="comment_odd">
                            @else
                                <div class="comment_even">
                        @endif
                        <strong>{{ $tag_review->score }}点</strong><br>
                        {{ $tag_review->comment }}<br>
                        {{ $tag_review->created_at }} <a
                            href="{{ route('user.show', ['user_id' => $tag_review->user->id]) }}">{{ $tag_review->user->name }}</a><br>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </section>
        <section class="anime_comment">
            <h2>コメント（新着順）</h2>
            @foreach ($anime->userReviews as $user_review)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
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
                    <br>
                    {{ $user_review->comment_timestamp }} <a
                        href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a><br>
                @endif
                </div>
            @endforeach
        </section>
        <section class="before_anime_information">
            <div class="row">
                <div class="col-sm-5">
                    <h2>視聴完了前統計情報</h2>
                    <div class="table-responsive">
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
                    </div>
                </div>
                <div class="col-sm-7">
                    <h2>広告</h2>
                    @if (env('APP_ENV') == 'production')
                        @include('layout.rakuten_adsense')
                    @endif
                </div>
        </section>
        <section class="before_anime_comment">
            <h2>視聴完了前コメント（新着順）</h2>
            @foreach ($anime->userReviews as $user_review)
                @if ($loop->iteration % 2 == 0)
                    <div class="comment_even">
                    @else
                        <div class="comment_odd">
                @endif
                @if (!is_null($user_review->before_comment))
                    @if (!is_null($user_review->before_score))
                        <strong>{{ $user_review->before_score }}点</strong><br>
                    @endif
                    {{ $user_review->before_comment }}<br>
                    {{ $user_review->before_comment_timestamp }} <a
                        href="{{ route('user.show', ['user_id' => $user_review->user->id]) }}">{{ $user_review->user->name }}</a><br>
                @endif
                </div>
            @endforeach
        </section>
        <section class="streaming_information">
            <h2>配信サイトのリンク</h2>
            <ul>
                <li>
                    @if (!is_null($anime->d_anime_store_id) &&
                        ($anime->d_anime_store_id != 'なし' && $anime->d_anime_store_id != 'レンタル'))
                        <a href="https://animestore.docomo.ne.jp/animestore/ci_pc?workId={{ $anime->d_anime_store_id }}"
                            target="_blank" rel="noopener noreferrer">dアニメストア</a>
                    @else
                        dアニメストア{{ $anime->d_anime_store_id == 'なし' ? '(配信なし)' : ($anime->d_anime_store_id == 'レンタル' ? '(レンタルあり)' : '(情報なし)') }}
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->amazon_prime_video_id) &&
                        ($anime->amazon_prime_video_id != 'なし' && $anime->amazon_prime_video_id != 'レンタル'))
                        <a href="https://www.amazon.co.jp/gp/video/detail/{{ $anime->amazon_prime_video_id }}"
                            target="_blank" rel="noopener noreferrer">Amazonプライムビデオ</a>
                    @else
                        Amazonプライムビデオ{{ $anime->amazon_prime_video_id == 'なし' ? '(配信なし)' : ($anime->amazon_prime_video_id == 'レンタル' ? '(レンタルあり)' : '(情報なし)') }}
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->fod_id) && ($anime->fod_id != 'なし' && $anime->fod_id != 'レンタル'))
                        <a href="https://fod.fujitv.co.jp/title/{{ $anime->fod_id }}" target="_blank"
                            rel="noopener noreferrer">FOD</a>
                    @else
                        FOD{{ $anime->fod_id == 'なし' ? '(配信なし)' : ($anime->fod_id == 'レンタル' ? '(レンタルあり)' : '(情報なし)') }}
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->unext_id) && ($anime->unext_id != 'なし' && $anime->unext_id != 'レンタル'))
                        <a href="{{ $anime->unext_id }}" target="_blank" rel="noopener noreferrer">U-NEXT</a>
                    @else
                        U-NEXT{{ $anime->unext_id == 'なし' ? '(配信なし)' : ($anime->unext_id == 'レンタル' ? '(レンタルあり)' : '(情報なし)') }}
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->abema_id) && ($anime->abema_id != 'なし' && $anime->abema_id != 'レンタル'))
                        <a href="https://abema.tv/video/title/{{ $anime->abema_id }}" target="_blank"
                            rel="noopener noreferrer">ABEMAプレミアム</a>
                    @else
                        ABEMAプレミアム{{ $anime->abema_id == 'なし' ? '(配信なし)' : ($anime->abema_id == 'レンタル' ? '(レンタルあり)' : '(情報なし)') }}
                    @endif
                </li>
                <li>
                    @if (!is_null($anime->disney_plus_id) &&
                        ($anime->disney_plus_id != 'なし' && $anime->disney_plus_id != 'レンタル'))
                        <a href="https://www.disneyplus.com/ja-jp/series/{{ $anime->disney_plus_id }}" target="_blank"
                            rel="noopener noreferrer">DISNEY+</a>
                    @else
                        DISNEY+{{ $anime->disney_plus_id == 'なし' ? '(配信なし)' : ($anime->disney_plus_id == 'レンタル' ? '(レンタルあり)' : '(情報なし)') }}
                    @endif
                </li>
                @if (env('APP_ENV') == 'production')
                    <a href="https://prf.hn/click/camref:1101lrEWa" rel="nofollow"><img
                            src="https://s3-ap-northeast-1.amazonaws.com/affiliate-img.docomo.ne.jp/banner/danimestore_220720_1_120x60.png"
                            style="width: 120px"></a>
                    <a href="https://px.a8.net/svt/ejp?a8mat=3NNF9T+4BZL9U+3250+5ZU29" rel="nofollow">
                        <img border="0" width="100" height="60" alt=""
                            src="https://www26.a8.net/svt/bgt?aid=221122577262&wid=001&eno=01&mid=s00000014274001007000&mc=1"></a>
                    <img border="0" width="1" height="1"
                        src="https://www16.a8.net/0.gif?a8mat=3NNF9T+4BZL9U+3250+5ZU29" alt="">
                    <a href="https://px.a8.net/svt/ejp?a8mat=3NNF9T+4D6GHE+4EKC+61Z81" rel="nofollow">
                        <img border="0" width="100" height="60" alt=""
                            src="https://www21.a8.net/svt/bgt?aid=221122577264&wid=001&eno=01&mid=s00000020550001017000&mc=1"></a>
                    <img border="0" width="1" height="1"
                        src="https://www15.a8.net/0.gif?a8mat=3NNF9T+4D6GHE+4EKC+61Z81" alt="">
                @endif
            </ul>
        </section>
        <section class="adsense">
            <h2>広告</h2>
            @if (env('APP_ENV') == 'production')
                @include('layout.horizontal_multiplex_adsense')
            @endif
        </section>
        <section class="anime_twitter">
            <h2>公式twitter</h2>
            <div class="anime_twitter">
                <a class="twitter-timeline" href="https://twitter.com/{{ $anime->twitter }}?ref_src=twsrc%5Etfw">Tweets
                    by
                    {{ $anime->twitter }}</a>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </section>
    </article>
@endsection
