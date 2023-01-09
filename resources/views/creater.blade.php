@extends('layout')

@section('title')
    <title>{{ $creater->name }} AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('creater', $creater) }}
@endsection

@section('sidebar_adsense')
    @include('layout.vertical_adsense')
@endsection

@section('main')
    <div id="app">
        <article class=creater_information>
            <h1>
                <a href="{{ $creater->url }}" target="_blank" rel="noopener noreferrer">{{ $creater->name }}</a>
            </h1>
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
                <h2>プロフィール</h2>
                <div class="table-responsive">
                    <table class="creater_profile_table">
                        <tr>
                            <th>読み</th>
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
                </div>
                <a
                    href="{{ route('modify_creater_request.show', ['creater_id' => $creater->id]) }}">クリエイターの情報の変更申請をする</a><br>
                <a href="{{ route('delete_creater_request.show', ['creater_id' => $creater->id]) }}">クリエイターの削除申請をする</a>
            </section>
            <section class="adsense">
                <h2>広告</h2>
                @if (env('APP_ENV') == 'production')
                    @include('layout.horizontal_adsense')
                @endif
            </section>
            <section class="creater_anime_list">
                @if (!$creater->animeCreaters->where('classification', 1)->isEmpty())
                <h2>監督</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 1)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 2)->isEmpty())
                <h2>脚本</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 2)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 3)->isEmpty())
                <h2>キャラクターデザイン</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 3)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 4)->isEmpty())
                <h2>シリーズ構成</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 4)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 5)->isEmpty())
                <h2>作画監督</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 5)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 6)->isEmpty())
                <h2>音楽</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 6)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 7)->isEmpty())
                <h2>歌手</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 7)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 8)->isEmpty())
                <h2>原作者</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 8)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if (!$creater->animeCreaters->where('classification', 100)->isEmpty())
                <h2>その他</h2>
                <div class="table-responsive">
                    <table class="creater_anime_list_table">
                        <tbody>
                            <tr>
                                <th>アニメ名</th>
                                <th>制作会社</th>
                                <th>放送クール</th>
                                <th>中央値</th>
                                <th>得点数</th>
                                <th>職種詳細</th>
                                @auth
                                    <th>つけた得点</th>
                                @endauth
                            </tr>
                            @foreach ($creater->animeCreaters->where('classification', 100)->unique('anime_id') as $animeCreater)
                                <tr>
                                    <td><a
                                            href="{{ route('anime.show', ['anime_id' => $animeCreater->anime->id]) }}">{{ $animeCreater->anime->title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($animeCreater->anime->companies as $company)
                                            <a
                                                href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $animeCreater->anime->year }}年{{ $animeCreater->anime->coor_label }}クール
                                    </td>
                                    <td>{{ $animeCreater->anime->median }}</td>
                                    <td>{{ $animeCreater->anime->count }}</td>
                                    <td>{{ $animeCreater->occupation }}</td>
                                    @auth
                                        <td>{{ $animeCreater->anime->userReview->score ?? '' }}</td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </section>
            @if (env('APP_ENV') == 'production')
                @include('layout.horizontal_multiplex_adsense')
            @endif
        </article>
    </div>
@endsection
@section('vue.js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
