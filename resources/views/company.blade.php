@extends('layout')

@section('title')
    <title>{{ $company->name }} AnimeScape</title>
@endsection

@section('main')
        <article class=company_information>
            <h2>
                <a href="{{ $company->public_url }}" target="_blank" rel="noopener noreferrer">{{ $company->name }}</a>
            </h2>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <span><strong>{{ $company->name }}</strong></span><br>
            <section class="company_anime_list">
                <h3>制作アニメ一覧（計{{ $company->animes->count() }}本）</h3>
                <table class="company_act_anime_list_table">
                    <tbody>
                        <tr>
                            <th>アニメ名</th>
                            <th>放送クール</th>
                            <th>中央値</th>
                            <th>データ数</th>
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
            </section>
        </article>
@endsection