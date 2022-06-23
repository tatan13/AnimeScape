@extends('layout')

@section('title')
    <title>{{ $user_information->name }}さんの情報 AnimeScape</title>
@endsection

@section('main')
    <div id="likeUser">
        <article class="user_information">
            <h2>{{ $user_information->name }}さんの情報</h2>
            <p>{{ $user_information->name }}</p>
            @auth
                @if (Auth::id() == $user_information->id)
                    <a href="{{ route('user_config.show') }}">個人情報設定</a>
                @else
                    <div v-if="isLikedUser">
                        <a href="#" @click="unlike(user_id)">お気に入りユーザーを解除する</a>
                    </div>
                    <div v-else>
                        <a href="#" @click="like(user_id)">お気に入りユーザーとして登録する</a>
                    </div>
                @endif
            @endauth
            @if (!is_null($user_information->one_comment))
                <p style="background-color: #FDFDAA;">{!! nl2br(e($user_information->one_comment)) !!}</p>
            @endif
            @if (!is_null($user_information->twitter))
                <p>Twitter : <a href="https://twitter.com/{{ $user_information->twitter }}" target="_blank"
                        rel="noopener noreferrer">{{ $user_information->twitter }}</a></p>
            @endif

            <section class="user_statistics_information">
                <div class="container-fruid">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>統計情報{{ !is_null($year) ? '(' . $year . '年' : '(すべて' }}{{ $coor != 0 ? App\Models\Anime::getCoorLabel($coor) . 'クール)' : ')' }}
                            </h3>
                            <table class="user_statistics_information">
                                <tbody>
                                    <tr>
                                        <th>得点入力数</th>
                                        <td><a
                                                href="{{ route('user_score_anime_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->score_count }}</a>
                                        </td>
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
                                                href="{{ route('user_will_watch_anime_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->will_watches_count }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>視聴数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_watch_anime_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->watches_count }}</a>
                                            </td>
                                    </tr>
                                    <tr>
                                        <th>視聴中数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_now_watch_anime_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->now_watches_count }}</a>
                                            </td>
                                    </tr>
                                    <tr>
                                        <th>視聴リタイア数</th>
                                        <td>
                                            <a
                                                href="{{ route('user_give_up_anime_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->give_ups_count }}</a>
                                            </td>
                                    </tr>
                                    <tr>
                                        <th>お気に入りユーザー数</th>
                                        <td><a
                                                href="{{ route('user_like_user_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->userLikeUsers->count() }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>被お気に入りユーザー数</th>
                                        <td><a
                                                href="{{ route('user_liked_user_list.show', ['user_id' => $user_information->id]) }}">@{{ likedUserCount }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>お気に入り声優数</th>
                                        <td><a
                                                href="{{ route('user_like_cast_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->likeCasts->count() }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>お気に入りクリエイター数</th>
                                        <td><a
                                                href="{{ route('user_like_creater_list.show', ['user_id' => $user_information->id]) }}">{{ $user_information->likeCreaters->count() }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3>得点分布</h3>
                            <table class="score_distribution_table">
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
                                            <td>{{ $user_information->{'score_' . $i . '_anime_reviews'}->count() + $user_information->{'score_' . $i + 5 . '_anime_reviews'}->count() }}
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <section class="anime_score_list">
                <h3>得点とアニメの対応表{{ !is_null($year) ? '(' . $year . '年' : '(すべて' }}{{ $coor != 0 ? App\Models\Anime::getCoorLabel($coor) . 'クール)' : ')' }}
                </h3>
                <form action="{{ route('user.show', ['user_id' => $user_information->id]) }}"
                    class="search_parameters_form" name="coor_score_animelist" method="get">
                    @csrf
                    @include('layout/select_year')
                    年
                    <select name="coor" class="coor">
                        <option value="">-</option>
                        <option value="1" {{ is_null($coor ?? null) ?'' : ($coor == 1 ? 'selected' : '') }}>冬
                        </option>
                        <option value="2" {{ is_null($coor ?? null) ?'' : ($coor == 2 ? 'selected' : '') }}>春
                        </option>
                        <option value="3" {{ is_null($coor ?? null) ?'' : ($coor == 3 ? 'selected' : '') }}>夏
                        </option>
                        <option value="4" {{ is_null($coor ?? null) ?'' : ($coor == 4 ? 'selected' : '') }}>秋
                        </option>
                    </select>
                    <input type="submit" value="絞り込み"> <a
                        href="{{ route('user.show', ['user_id' => $user_information->id]) }}">絞り込み解除</a>
                </form>
                <table class="anime_score_list_table">
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
                                    <ul class="list-inline d-inline">
                                        @foreach ($user_information->{'score_' . $i . '_anime_reviews'} as ${'score_' . $i . '_anime_review'})
                                            <li class="d-inline">
                                                <span
                                                    style="font-size: 50%;">{{ ${'score_' . $i . '_anime_review'}->score }}</span>
                                                <a
                                                    href="{{ route('anime.show', ['anime_id' => ${'score_' . $i . '_anime_review'}->anime->id]) }}">{{ ${'score_' . $i . '_anime_review'}->anime->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </section>
        </article>
    </div>
@endsection
@section('vue.js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const vue = new Vue({
            el: '#likeUser',
            data() {
                return {
                    user_id: '{{ $user_information->id }}',
                    likedUserCount: '{{ $user_information->userLikedUsers->count() }}',
                    @auth
                    isLikedUser: '{{ Auth::user()->isLikeUser($user_information->id) }}',
                @endauth
            };
        },
        methods: {
            like(user_id) {
                let url = `/user_information/${user_id}/like`
                axios.get(url)
                    .then(response => {
                        this.likedUserCount = response.data.likedUserCount
                        this.isLikedUser = true
                    })
                    .catch(error => {
                        alert(error)
                    });
            },
            unlike(user_id) {
                let url = `/user_information/${user_id}/unlike`
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
