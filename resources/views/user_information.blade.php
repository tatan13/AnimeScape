@extends('layout')

@section('title')
    <title>{{ $user->uid }}さんの情報 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="likeUser">
        <h2>{{ $user->uid }}さんの情報</h2>
        <p>{{ $user->uid }}</p>
        @auth
            @if (strcmp(Auth::user()->uid, $user->uid) == 0)
                <a href="{{ route('user.config', ['uid' => Auth::user()->uid]) }}">個人情報設定</a>
            @else
                <div v-if="isLikedUser">
                    <a href="#" @click="dislike(uid)">お気に入りユーザーを解除する</a>
                </div>
                <div v-else>
                    <a href="#" @click="like(uid)">お気に入りユーザーとして登録する</a>
                </div>
            @endif
        @endauth
        @if (!is_null($user->onewordcomment))
            <div id="one_comment">
                <p class="one_comment">{{ $user->onewordcomment }}</p>
            </div>
        @endif
        @if (!is_null($user->twitter))
            <p>Twitter : <a href="https://twitter.com/{{ $user->twitter }}" target="_blank"
                    rel="noopener noreferrer">{{ $user->twitter }}</a></p>
        @endif
        <div class="container-fruid">
            <div class="row">
                <div class="col-md-6">
                    <h3>統計情報{{ !is_null($year) ? '(' . $year . '年' : '(すべて' }}{{ !is_null($coor->getNum()) ? $coor->getLabel() . 'クール)' : ')' }}
                    </h3>
                    <table id="statistics_information">
                        <tbody>
                            <tr>
                                <th>得点入力数</th>
                                <td>{{ $score_count }}</td>
                            </tr>
                            <tr>
                                <th>得点の平均</th>
                                <td>{{ $score_average }}</td>
                            </tr>
                            <tr>
                                <th>得点の中央値</th>
                                <td>{{ $score_median }}</td>
                            </tr>
                            <tr>
                                <th>一言感想入力数</th>
                                <td>{{ $one_comments_count }}</td>
                            </tr>
                            <tr>
                                <th>視聴予定数</th>
                                <td><a
                                        href="{{ route('user.will_watch_list', ['uid' => $user->uid]) }}">{{ $will_watches_count }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>視聴数</th>
                                <td>{{ $watches_count }}</td>
                            </tr>
                            <tr>
                                <th>お気に入りユーザー数</th>
                                <td><a
                                        href="{{ route('user.like_user_list', ['uid' => $user->uid]) }}">{{ count($user->userLikeUsers()->get()) }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>被お気に入りユーザー数</th>
                                <td><a
                                        href="{{ route('user.liked_user_list', ['uid' => $user->uid]) }}">@{{ likedUserCount }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>お気に入り声優数</th>
                                <td><a
                                        href="{{ route('user.like_cast_list', ['uid' => $user->uid]) }}">{{ count($user->likeCasts()->get()) }}</a>
                                </td>
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
                                <td>{{ count($score_100_anime_reviews) }}</td>
                            </tr>
                            <tr>
                                <td>90～99</td>
                                <td>{{ count($score_95_anime_reviews) + count($score_90_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>80～89</td>
                                <td>{{ count($score_85_anime_reviews) + count($score_80_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>70～79</td>
                                <td>{{ count($score_75_anime_reviews) + count($score_70_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>60～69</td>
                                <td>{{ count($score_65_anime_reviews) + count($score_60_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>50～59</td>
                                <td>{{ count($score_55_anime_reviews) + count($score_50_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>40～49</td>
                                <td>{{ count($score_45_anime_reviews) + count($score_40_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>30～39</td>
                                <td>{{ count($score_35_anime_reviews) + count($score_30_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>20～29</td>
                                <td>{{ count($score_25_anime_reviews) + count($score_20_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>10～19</td>
                                <td>{{ count($score_15_anime_reviews) + count($score_10_anime_reviews) }}
                                </td>
                            </tr>
                            <tr>
                                <td>0～9</td>
                                <td>{{ count($score_5_anime_reviews) + count($score_0_anime_reviews) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <h3>得点とアニメの対応表{{ !is_null($year) ? '(' . $year . '年' : '(すべて' }}{{ !is_null($coor->getNum()) ? $coor->getLabel() . 'クール)' : ')' }}
        </h3>
        <div class="container-fruid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('user', ['uid' => $user->uid]) }}" name="coor_score_animelist" method="get">
                        @csrf
                        <select name="year" id="coor_year">
                            <option value="2022" {{ $year == 2022 ? 'selected' : '' }}>2022</option>
                            <option value="2021" {{ $year == 2021 ? 'selected' : '' }}>2021</option>
                        </select>
                        年
                        <select name="coor" id="coor">
                            <option value="">-</option>
                            <option value="1" {{ $coor->getNum() == 1 ? 'selected' : '' }}>冬</option>
                            <option value="2" {{ $coor->getNum() == 2 ? 'selected' : '' }}>春</option>
                            <option value="3" {{ $coor->getNum() == 3 ? 'selected' : '' }}>夏</option>
                            <option value="4" {{ $coor->getNum() == 4 ? 'selected' : '' }}>秋</option>
                        </select>
                        <input type="submit" value="絞り込み"> <a
                            href="{{ route('user', ['uid' => $user->uid]) }}">絞り込み解除</a>
                    </form>
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
                                        @foreach ($score_100_anime_reviews as $score_100_anime_review)
                                            <li>
                                                <span class="score">{{ $score_100_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_100_anime_review->anime->id]) }}">{{ $score_100_anime_review->anime->title }}
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
                                        @foreach ($score_95_anime_reviews as $score_95_anime_review)
                                            <li>
                                                <span class="score">{{ $score_95_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_95_anime_review->anime->id]) }}">{{ $score_95_anime_review->anime->title }}
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
                                        @foreach ($score_90_anime_reviews as $score_90_anime_review)
                                            <li>
                                                <span class="score">{{ $score_90_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_90_anime_review->anime->id]) }}">{{ $score_90_anime_review->anime->title }}
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
                                        @foreach ($score_85_anime_reviews as $score_85_anime_review)
                                            <li>
                                                <span class="score">{{ $score_85_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_85_anime_review->anime->id]) }}">{{ $score_85_anime_review->anime->title }}
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
                                        @foreach ($score_80_anime_reviews as $score_80_anime_review)
                                            <li>
                                                <span class="score">{{ $score_80_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_80_anime_review->anime->id]) }}">{{ $score_80_anime_review->anime->title }}
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
                                        @foreach ($score_75_anime_reviews as $score_75_anime_review)
                                            <li>
                                                <span class="score">{{ $score_75_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_75_anime_review->anime->id]) }}">{{ $score_75_anime_review->anime->title }}
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
                                        @foreach ($score_70_anime_reviews as $score_70_anime_review)
                                            <li>
                                                <span class="score">{{ $score_70_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_70_anime_review->anime->id]) }}">{{ $score_70_anime_review->anime->title }}
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
                                        @foreach ($score_65_anime_reviews as $score_65_anime_review)
                                            <li>
                                                <span class="score">{{ $score_65_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_65_anime_review->anime->id]) }}">{{ $score_65_anime_review->anime->title }}
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
                                        @foreach ($score_60_anime_reviews as $score_60_anime_review)
                                            <li>
                                                <span class="score">{{ $score_60_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_60_anime_review->anime->id]) }}">{{ $score_60_anime_review->anime->title }}
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
                                        @foreach ($score_55_anime_reviews as $score_55_anime_review)
                                            <li>
                                                <span class="score">{{ $score_55_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_55_anime_review->anime->id]) }}">{{ $score_55_anime_review->anime->title }}
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
                                        @foreach ($score_50_anime_reviews as $score_50_anime_review)
                                            <li>
                                                <span class="score">{{ $score_50_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_50_anime_review->anime->id]) }}">{{ $score_50_anime_review->anime->title }}
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
                                        @foreach ($score_45_anime_reviews as $score_45_anime_review)
                                            <li>
                                                <span class="score">{{ $score_45_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_45_anime_review->anime->id]) }}">{{ $score_45_anime_review->anime->title }}
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
                                        @foreach ($score_40_anime_reviews as $score_40_anime_review)
                                            <li>
                                                <span class="score">{{ $score_40_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_40_anime_review->anime->id]) }}">{{ $score_40_anime_review->anime->title }}
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
                                        @foreach ($score_35_anime_reviews as $score_35_anime_review)
                                            <li>
                                                <span class="score">{{ $score_35_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_35_anime_review->anime->id]) }}">{{ $score_35_anime_review->anime->title }}
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
                                        @foreach ($score_30_anime_reviews as $score_30_anime_review)
                                            <li>
                                                <span class="score">{{ $score_30_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_30_anime_review->anime->id]) }}">{{ $score_30_anime_review->anime->title }}
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
                                        @foreach ($score_25_anime_reviews as $score_25_anime_review)
                                            <li>
                                                <span class="score">{{ $score_25_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_25_anime_review->anime->id]) }}">{{ $score_25_anime_review->anime->title }}
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
                                        @foreach ($score_20_anime_reviews as $score_20_anime_review)
                                            <li>
                                                <span class="score">{{ $score_20_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_20_anime_review->anime->id]) }}">{{ $score_20_anime_review->anime->title }}
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
                                        @foreach ($score_15_anime_reviews as $score_15_anime_review)
                                            <li>
                                                <span class="score">{{ $score_15_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_15_anime_review->anime->id]) }}">{{ $score_15_anime_review->anime->title }}
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
                                        @foreach ($score_10_anime_reviews as $score_10_anime_review)
                                            <li>
                                                <span class="score">{{ $score_10_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_10_anime_review->anime->id]) }}">{{ $score_10_anime_review->anime->title }}
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
                                        @foreach ($score_5_anime_reviews as $score_5_anime_review)
                                            <li>
                                                <span class="score">{{ $score_5_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_5_anime_review->anime->id]) }}">{{ $score_5_anime_review->anime->title }}
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
                                        @foreach ($score_0_anime_reviews as $score_0_anime_review)
                                            <li>
                                                <span class="score">{{ $score_0_anime_review->score }}</span>
                                                <a
                                                    href="{{ route('anime', ['id' => $score_0_anime_review->anime->id]) }}">{{ $score_0_anime_review->anime->title }}
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
    </div>
@endsection
@section('vue.js')
    <script>
        const vue = new Vue({
            el: '#likeUser',
            data() {
                return {
                    uid: '{{ $user->uid }}',
                    likedUserCount: '{{ $user->userLikedUsers->count() }}',
                    @auth
                        isLikedUser: '{{ Auth::user()->isLikeUser($user->uid) }}',
                    @endauth
                };
            },
            methods: {
                like(uid) {
                    let url = `/user_information/${uid}/like`
                    axios.get(url)
                        .then(response => {
                            this.likedUserCount = response.data.likedUserCount
                            this.isLikedUser = true
                        })
                        .catch(error => {
                            alert(error)
                        });
                },
                dislike(uid) {
                    let url = `/user_information/${uid}/dislike`
                    axios.get(url)
                        .then(response => {
                            this.likedUserCount = response.data.likedUserCount
                            this.isLikedUser = false
                        })
                        .catch(error => {
                            alert(error)
                        });
                },
            },
            mounted() {
                console.log('Component mounted.')
            }
        });
    </script>
@endsection
