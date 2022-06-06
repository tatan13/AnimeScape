@extends('layout')

@section('title')
    <title>{{ $cast->name }} AnimeScape</title>
@endsection

@section('main')
    <div id="app">
        <article class=cast_information>
            <h2>
                <a href="{{ $cast->url }}" target="_blank" rel="noopener noreferrer">{{ $cast->name }}</a>
            </h2>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <span><strong>{{ $cast->name }}</strong></span><br>
            @auth
                <like-cast-component :props-cast-id="{{ json_encode($cast->id) }}"
                    :default-is-like-cast="{{ json_encode(Auth::user()->isLikeCast($cast->id)) }}">
                    ></like-cast-component>
            @endauth
            <section class="cast_profile">
                <h3>プロフィール</h3>
                <table class="cast_profile_table">
                    <tr>
                        <th>ふりがな</th>
                        <td>{{ $cast->furigana }}</td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>{{ $cast->sex_label }}</td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td>{{ $cast->birth }}</td>
                    </tr>
                    <tr>
                        <th>出身地</th>
                        <td>{{ $cast->birthplace }}</td>
                    </tr>
                    <tr>
                        <th>血液型</th>
                        <td>{{ $cast->blood_type }}</td>
                    </tr>
                    <tr>
                        <th>所属事務所</th>
                        <td><a href="{{ $cast->url }}" target="_blank"
                                rel="noopener noreferrer">{{ $cast->office }}</a></td>
                    </tr>
                    <tr>
                        <th>ツイッター</th>
                        <td><a href="https://twitter.com/{{ $cast->twitter }}" target="_blank"
                                rel="noopener noreferrer">{{ $cast->twitter }}</a></td>
                    </tr>
                    <tr>
                        <th>公式ブログ</th>
                        <td><a href="{{ $cast->blog_url }}" target="_blank"
                                rel="noopener noreferrer">{{ $cast->blog }}</a></td>
                    </tr>
                </table>
                <a href="{{ route('modify_cast_request.show', ['cast_id' => $cast->id]) }}">声優の情報の変更申請をする</a><br>
                <a href="{{ route('delete_cast_request.show', ['cast_id' => $cast->id]) }}">声優の削除申請をする</a>
            </section>
            <section class="cast_act_anime_list">
                <h3>出演アニメ（計{{ $cast->actAnimes->count() }}本）</h3>
                <table class="cast_act_anime_list_table">
                    <tbody>
                        <tr>
                            <th>アニメ名</th>
                            <th>制作会社</th>
                            <th>放送クール</th>
                            <th>中央値</th>
                            <th>データ数</th>
                            @auth
                                <th>つけた得点</th>
                            @endauth
                        </tr>
                        @foreach ($cast->actAnimes as $act_anime)
                            <tr>
                                <td><a
                                        href="{{ route('anime.show', ['anime_id' => $act_anime->id]) }}">{{ $act_anime->title }}</a>
                                </td>
                                <td>
                                    @foreach ($act_anime->companies as $company)
                                        <a
                                            href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $act_anime->year }}年{{ $act_anime->coor_label }}クール
                                </td>
                                <td>{{ $act_anime->median }}</td>
                                <td>{{ $act_anime->count }}</td>
                                @auth
                                    <td>{{ $act_anime->userReview->score ?? '' }}</td>
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </article>
    </div>
@endsection
@section('vue.js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
