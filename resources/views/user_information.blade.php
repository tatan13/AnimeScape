@extends('layout')

@section('title')
    <title>{{ $user_information->uid }}さんの情報 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="likeUser">
        <h2>{{ $user_information->uid }}さんの情報</h2>
        <p>{{ $user_information->uid }}</p>
        @auth
            @if (strcmp(Auth::user()->uid, $user_information->uid) == 0)
                <a href="{{ route('user_config.show') }}">個人情報設定</a>
            @else
                <div v-if="isLikedUser">
                    <a href="#" @click="unlike(uid)">お気に入りユーザーを解除する</a>
                </div>
                <div v-else>
                    <a href="#" @click="like(uid)">お気に入りユーザーとして登録する</a>
                </div>
            @endif
        @endauth
        @if (!is_null($user_information->onewordcomment))
            <div id="one_comment">
                <p class="one_comment">{{ $user_information->onewordcomment }}</p>
            </div>
        @endif
        @if (!is_null($user_information->twitter))
            <p>Twitter : <a href="https://twitter.com/{{ $user_information->twitter }}" target="_blank"
                    rel="noopener noreferrer">{{ $user_information->twitter }}</a></p>
        @endif
        <div class="container-fruid">
            <div class="row">
                <div class="col-md-6">
                    <h3>統計情報{{ !is_null($year) ? '(' . $year . '年' : '(すべて' }}{{ $coor != 0 ? App\Models\Anime::getCoorLabel($coor) . 'クール)' : ')' }}
                    </h3>
                    <table id="statistics_information">
                        <tbody>
                            <tr>
                                <th>得点入力数</th>
                                <td>{{ $user_information->score_count }}</td>
                            </tr>
                            <tr>
                                <th>得点の平均</th>
                                <td>{{ $user_information->score_average }}</td>
                            </tr>
                            <tr>
                                <th>得点の中央値</th>
                                <td>{{ $user_information->score_median }}</td>
                            </tr>
                            <tr>
                                <th>一言感想入力数</th>
                                <td>{{ $user_information->one_comments_count }}</td>
                            </tr>
                            <tr>
                                <th>視聴予定数</th>
                                <td><a
                                        href="{{ route('user_will_watch_anime_list.show', ['uid' => $user_information->uid]) }}">{{ $user_information->will_watches_count }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>視聴数</th>
                                <td>{{ $user_information->watches_count }}</td>
                            </tr>
                            <tr>
                                <th>お気に入りユーザー数</th>
                                <td><a
                                        href="{{ route('user_like_user_list.show', ['uid' => $user_information->uid]) }}">{{ $user_information->userLikeUsers->count() }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>被お気に入りユーザー数</th>
                                <td><a
                                        href="{{ route('user_liked_user_list.show', ['uid' => $user_information->uid]) }}">@{{ likedUserCount }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>お気に入り声優数</th>
                                <td><a
                                        href="{{ route('user_like_cast_list.show', ['uid' => $user_information->uid]) }}">{{ $user_information->likeCasts->count() }}</a>
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
                                <td>{{ $user_information['score_100_anime_reviews']->count() }}</td>
                            </tr>
                            @for ($i = 90; $i >= 0; $i = $i - 10)
                                <tr>
                                    <td>{{ $i }}～{{ $i + 9 }}</td>
                                    <td>{{ $user_information->{'score_' . $i . '_anime_reviews'}->count() +$user_information->{'score_' . $i + 5 . '_anime_reviews'}->count() }}
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <h3>得点とアニメの対応表{{ !is_null($year) ? '(' . $year . '年' : '(すべて' }}{{ $coor != 0 ? App\Models\Anime::getCoorLabel($coor) . 'クール)' : ')' }}
        </h3>
        <div class="container-fruid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('user.show', ['uid' => $user_information->uid]) }}" name="coor_score_animelist"
                        method="get">
                        @csrf
                        <select name="year" id="coor_year">
                            <option value="2022" {{ $year == 2022 ? 'selected' : '' }}>2022</option>
                            <option value="2021" {{ $year == 2021 ? 'selected' : '' }}>2021</option>
                        </select>
                        年
                        <select name="coor" id="coor">
                            <option value="">-</option>
                            <option value="1" {{ $coor == 1 ? 'selected' : '' }}>冬</option>
                            <option value="2" {{ $coor == 2 ? 'selected' : '' }}>春</option>
                            <option value="3" {{ $coor == 3 ? 'selected' : '' }}>夏</option>
                            <option value="4" {{ $coor == 4 ? 'selected' : '' }}>秋</option>
                        </select>
                        <input type="submit" value="絞り込み"> <a
                            href="{{ route('user.show', ['uid' => $user_information->uid]) }}">絞り込み解除</a>
                    </form>
                    <table id="anime_score_list">
                        <thead>
                            <tr>
                                <th>得点</th>
                                <th>アニメ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 100; $i >= 0; $i = $i - 5)
                                <tr>
                                    <th>{{ $i }}</th>
                                    <td>
                                        <ul>
                                            @foreach ($user_information->{'score_' . $i . '_anime_reviews'} as ${'score_' . $i . '_anime_review'})
                                                <li>
                                                    <span
                                                        class="score">{{ ${'score_' . $i . '_anime_review'}->score }}</span>
                                                    <a
                                                        href="{{ route('anime.show', ['id' => ${'score_' . $i . '_anime_review'}->anime->id]) }}">{{ ${'score_' . $i . '_anime_review'}->anime->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endfor
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const vue = new Vue({
            el: '#likeUser',
            data() {
                return {
                    uid: '{{ $user_information->uid }}',
                    likedUserCount: '{{ $user_information->userLikedUsers->count() }}',
                    @auth
                        isLikedUser: '{{ Auth::user()->isLikeUser($user_information->id) }}',
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
                unlike(uid) {
                    let url = `/user_information/${uid}/unlike`
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
        });
    </script>
@endsection
