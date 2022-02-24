@extends('layout')

@section('title')
    <title>{{ $user_information['user']->uid }}さんの情報 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div class="col-md-8">
        <div id="main">
            <h2>{{ $user_information['user']->uid }}さんの情報</h2>
            <p>{{ $user_information['user']->uid }}</p>
            @auth
                @if(strcmp(Auth::user()->uid, $user_information['user']->uid) == 0)
                    <a href="{{ route('user.config', ['uid' => Auth::user()->uid]) }}">個人情報設定</a>
                @else
                    @if(!Auth::user()->user_like_users->contains('liked_user_id', $user_information['user']->id))
                        <a href="{{ route('user.like', ['uid' => $user_information['user']->uid]) }}">お気に入りユーザーに登録する</a>
                    @else
                        <a href="{{ route('user.dislike', ['uid' => $user_information['user']->uid]) }}">お気に入りユーザーを解除する</a>
                    @endif
                @endif
            @endauth
            @if(!is_null($user_information['user']->onewordcomment))
                <div id="one_comment">
                    <p class="one_comment">{{ $user_information['user']->onewordcomment }}</p>
                </div>
            @endif
            @if(!is_null($user_information['user']->twitter))
            Twitter : <a href="https://twitter.com/{{ $user_information['user']->twitter }}" target="_blank" rel="noopener noreferrer">{{ $user_information['user']->twitter }}</a>
            @endif
            <div class="container-fruid">
                <div class="row">
                    <div class="col-md-6">
                        <h3>統計情報</h3>
                        <table id="statistics_information">
                            <tbody>
                                <tr>
                                    <th>得点入力数</th>
                                    <td>{{ $user_information['score_count'] }}</td>
                                </tr>
                                <tr>
                                    <th>得点の平均</th>
                                    <td>{{ $user_information['score_average'] }}</td>
                                </tr>
                                <tr>
                                    <th>得点の中央値</th>
                                    <td>{{ $user_information['score_median'] }}</td>
                                </tr>
                                <tr>
                                    <th>一言感想入力数</th>
                                    <td>{{ $user_information['one_comments_count'] }}</td>
                                </tr>
                                <tr>
                                    <th>視聴予定数</th>
                                    <td><a href="{{ route('user.will_watch_list', ['uid' => $user_information['user']->uid]) }}">{{ $user_information['will_watches_count'] }}</a></td>
                                </tr>
                                <tr>
                                    <th>視聴数</th>
                                    <td>{{ $user_information['watches_count'] }}</td>
                                </tr>
                                <tr>
                                    <th>お気に入りユーザー数</th>
                                    <td><a href="{{ route('user.like_user_list', ['uid' => $user_information['user']->uid]) }}">{{ count($user_information['user']->user_like_users()->get()) }}</a></td>
                                </tr>
                                <tr>
                                    <th>被お気に入りユーザー数</th>
                                    <td><a href="{{ route('user.liked_user_list', ['uid' => $user_information['user']->uid]) }}">{{ count($user_information['user']->user_liked_users()->get()) }}</a></td>
                                </tr>
                                <tr>
                                    <th>お気に入り声優数</th>
                                    <td><a href="{{ route('user.like_cast_list', ['uid' => $user_information['user']->uid]) }}">{{ count($user_information['user']->like_casts()->get()) }}</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h3>得点分布</h3>
                        <table id="score_distribution">
                            <tbody>
                                <tr>
                                    <th>状況</th>
                                    <th>度数</th>
                                </tr>
                                <tr>
                                    <td>100</td>
                                    <td>{{ count($score_n_animes['score_100_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>90～99</td>
                                    <td>{{ count($score_n_animes['score_95_animes']) + count($score_n_animes['score_90_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>80～89</td>
                                    <td>{{ count($score_n_animes['score_85_animes']) + count($score_n_animes['score_80_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>70～79</td>
                                    <td>{{ count($score_n_animes['score_75_animes']) + count($score_n_animes['score_70_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>60～69</td>
                                    <td>{{ count($score_n_animes['score_65_animes']) + count($score_n_animes['score_60_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>50～59</td>
                                    <td>{{ count($score_n_animes['score_55_animes']) + count($score_n_animes['score_50_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>40～49</td>
                                    <td>{{ count($score_n_animes['score_45_animes']) + count($score_n_animes['score_40_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>30～39</td>
                                    <td>{{ count($score_n_animes['score_35_animes']) + count($score_n_animes['score_30_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>20～29</td>
                                    <td>{{ count($score_n_animes['score_25_animes']) + count($score_n_animes['score_20_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>10～19</td>
                                    <td>{{ count($score_n_animes['score_15_animes']) + count($score_n_animes['score_10_animes']) }}</td>
                                </tr>
                                <tr>
                                    <td>0～9</td>
                                    <td>{{ count($score_n_animes['score_5_animes']) + count($score_n_animes['score_0_animes']) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <h3>得点とゲームの対応表</h3>
            <div class="container-fruid">
                <div class="row">
                    <div class="col-md-6">
                        <table id="anime_score_list">
                            <thead>
                                <tr>
                                    <th>得点</th>
                                    <th>アニメ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>100</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_100_animes'] as $score_100_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_100_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_100_anime['anime']->id]) }}">{{ $score_100_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>95</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_95_animes'] as $score_95_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_95_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_95_anime['anime']->id]) }}">{{ $score_95_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>90</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_90_animes'] as $score_90_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_90_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_90_anime['anime']->id]) }}">{{ $score_90_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>85</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_85_animes'] as $score_85_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_85_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_85_anime['anime']->id]) }}">{{ $score_85_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>80</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_80_animes'] as $score_80_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_80_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_80_anime['anime']->id]) }}">{{ $score_80_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>75</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_75_animes'] as $score_75_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_75_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_75_anime['anime']->id]) }}">{{ $score_75_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>70</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_70_animes'] as $score_70_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_70_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_70_anime['anime']->id]) }}">{{ $score_70_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>65</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_65_animes'] as $score_65_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_65_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_65_anime['anime']->id]) }}">{{ $score_65_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>60</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_60_animes'] as $score_60_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_60_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_60_anime['anime']->id]) }}">{{ $score_60_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>55</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_55_animes'] as $score_55_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_55_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_55_anime['anime']->id]) }}">{{ $score_55_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>50</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_50_animes'] as $score_50_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_50_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_50_anime['anime']->id]) }}">{{ $score_50_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>45</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_45_animes'] as $score_45_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_45_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_45_anime['anime']->id]) }}">{{ $score_45_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>40</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_40_animes'] as $score_40_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_40_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_40_anime['anime']->id]) }}">{{ $score_40_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>35</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_35_animes'] as $score_35_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_35_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_35_anime['anime']->id]) }}">{{ $score_35_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>30</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_30_animes'] as $score_30_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_30_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_30_anime['anime']->id]) }}">{{ $score_30_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>25</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_25_animes'] as $score_25_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_25_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_25_anime['anime']->id]) }}">{{ $score_25_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>20</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_20_animes'] as $score_20_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_20_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_20_anime['anime']->id]) }}">{{ $score_20_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>15</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_15_animes'] as $score_15_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_15_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_15_anime['anime']->id]) }}">{{ $score_15_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>10</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_10_animes'] as $score_10_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_10_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_10_anime['anime']->id]) }}">{{ $score_10_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>5</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_5_animes'] as $score_5_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_5_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_5_anime['anime']->id]) }}">{{ $score_5_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>0</th>
                                    <td>
                                        <ul>
                                            @foreach ($score_n_animes['score_0_animes'] as $score_0_anime)
                                                <li>
                                                    <span
                                                        class="score">{{ $score_0_anime['my_anime_score'] }}</span>
                                                    <a
                                                        href="{{ route('anime', ['id' => $score_0_anime['anime']->id]) }}">{{ $score_0_anime['anime']->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
