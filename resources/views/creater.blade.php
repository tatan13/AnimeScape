@extends('layout')

@section('title')
    <title>{{ $creater->name }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('title_adsense')
    @include('layout.horizontal_adsense')
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <div id="app">
        <article class=creater_information>
            <h2>
                <a href="{{ $creater->url }}" target="_blank" rel="noopener noreferrer">{{ $creater->name }}</a>
            </h2>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <div class="title">{{ $creater->name }}</div>
            @auth
                <like-creater-component :props-creater-id="{{ json_encode($creater->id) }}"
                    :default-is-like-creater="{{ json_encode(Auth::user()->isLikeCreater($creater->id)) }}">
                </like-creater-component>
            @endauth
            <section class="creater_profile">
                <h3>プロフィール</h3>
                <table class="creater_profile_table">
                    <tr>
                        <th>ふりがな</th>
                        <td>{{ $creater->furigana }}</td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>{{ $creater->sex_label }}</td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td>{{ $creater->birth }}</td>
                    </tr>
                    <tr>
                        <th>出身地</th>
                        <td>{{ $creater->birthplace }}</td>
                    </tr>
                    <tr>
                        <th>血液型</th>
                        <td>{{ $creater->blood_type }}</td>
                    </tr>
                    <tr>
                        <th>ツイッター</th>
                        <td><a href="https://twitter.com/{{ $creater->twitter }}" target="_blank"
                                rel="noopener noreferrer">{{ $creater->twitter }}</a></td>
                    </tr>
                    <tr>
                        <th>公式ブログ</th>
                        <td><a href="{{ $creater->blog_url }}" target="_blank"
                                rel="noopener noreferrer">{{ $creater->blog }}</a></td>
                    </tr>
                </table>
                <a
                    href="{{ route('modify_creater_request.show', ['creater_id' => $creater->id]) }}">クリエイターの情報の変更申請をする</a><br>
                <a href="{{ route('delete_creater_request.show', ['creater_id' => $creater->id]) }}">クリエイターの削除申請をする</a>
            </section>
            <section class="creater_act_anime_list">
                <h3>（計{{ $creater->animes->count() }}本）</h3>
                <table class="creater_anime_list_table">
                    <tbody>
                        <tr>
                            <th>アニメ名</th>
                            <th>制作会社</th>
                            <th>放送クール</th>
                            <th>中央値</th>
                            <th>得点数</th>
                            @auth
                                <th>つけた得点</th>
                            @endauth
                        </tr>
                        @foreach ($creater->animes as $anime)
                            <tr>
                                <td><a
                                        href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
                                </td>
                                <td>
                                    @foreach ($anime->companies as $company)
                                        <a
                                            href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $anime->year }}年{{ $anime->coor_label }}クール
                                </td>
                                <td>{{ $anime->median }}</td>
                                <td>{{ $anime->count }}</td>
                                @auth
                                    <td>{{ $anime->userReview->score ?? '' }}</td>
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
