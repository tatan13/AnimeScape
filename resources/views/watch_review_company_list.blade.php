@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの制作会社別視聴数 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
<article class="watch_review_company_list">
    <h1>{{ $user->name }}さんの制作会社別視聴数</h1>
            <section class="watch_review_company_list">
                <h2>制作会社別視聴数</h2>
                <div class="table-responsive">
                    <table class="watch_review_company_list_table">
                        <tr>
                            <th>会社名</th>
                            <th>視聴数</th>
                            <th>得点平均</th>
                            <th>アニメ</th>
                        </tr>
                        @foreach ($company_list as $company)
                            <tr>
                                <td><a href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                </td>
                                <td>{{ $company->animes_count }}</td>
                                <td>{{ $company->animes->average('userReview.score') }}</td>
                                <td>
                                    <ul class="list-inline d-inline">
                                        @foreach ($company->animes->sortByDesc('userReview.score') as $anime)
                                            <li class="d-inline">
                                                <span style="font-size: 50%;">{{ $anime->userReview->score }}</span>
                                                <a href="{{ route('anime.show', ['anime_id' => $anime->id]) }}">{{ $anime->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </section>
        </article>
    </div>
@endsection
