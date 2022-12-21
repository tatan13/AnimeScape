@extends('layout')

@section('title')
    <title>{{ $company->name }} AnimeScape -アニメ批評空間-</title>
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
    <article class=company_information>
        <h1>
            <a href="{{ $company->public_url }}" target="_blank" rel="noopener noreferrer">{{ $company->name }}</a>
        </h1>
        @if (session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        <div class="title">{{ $company->name }}</div>
        <section class="company_profile">
            <a href="{{ route('delete_company_request.show', ['company_id' => $company->id]) }}">制作会社の削除申請をする</a>
            <h2>統計情報</h2>
            <div class="table-responsive">
                <table class="cast_profile_table">
                    <tr>
                        <th>中央値</th>
                        <td>{{ $user_reviews->whereNotNull('score')->median('score') }}</td>
                    </tr>
                    <tr>
                        <th>平均値</th>
                        <td>{{ $user_reviews->whereNotNull('score')->average('score') }}</td>
                    </tr>
                    <tr>
                        <th>総得点数</th>
                        <td>{{ $user_reviews->whereNotNull('score')->count() }}</td>
                    </tr>
                    <tr>
                        <th>ユーザー数</th>
                        <td>{{ $user_reviews->whereNotNull('score')->unique('user_id')->count() }}</td>
                    </tr>
                </table>
            </div>
        </section>
        <section class="company_anime_list">
            <h2>制作アニメ一覧（計{{ $company->animes->count() }}本）</h2>
            <div class="table-responsive">
                <table class="company_anime_list_table">
                    <tbody>
                        <tr>
                            <th>アニメ名</th>
                            <th>放送クール</th>
                            <th>中央値</th>
                            <th>得点数</th>
                            @auth
                                <th>つけた得点</th>
                            @endauth
                        </tr>
                        @foreach ($company->animes as $anime)
                            <tr>
                                <td><a
                                        href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}</a>
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
            </div>
        </section>
        @if (env('APP_ENV') == 'production')
            @include('layout.horizontal_multiplex_adsense')
        @endif
    </article>
@endsection
