@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの得点を付けたアニメリスト AnimeScape</title>
@endsection

@section('main')
    <article class="score_anime_list">
        <h2>{{ $user->name }}さんの得点を付けたアニメリスト</h2>
        <strong>{{ $user->name }}</strong>
        <h3>得点を付けたアニメリスト</h3>
        <table class="score_anime_list_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>会社名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>データ数</th>
                    <th>つけた得点</th>
                    <th>作成日</th>
                </tr>
                @foreach ($score_review_list as $score_review)
                    <tr>
                        <td><a
                                href="{{ route('anime.show', ['id' => $score_review->anime->id]) }}">{{ $score_review->anime->title }}</a>
                        </td>
                        <td>{{ $score_review->anime->company }}</td>
                        <td>
                            {{ $score_review->anime->year }}年{{ $score_review->anime->coor_label }}クール
                        </td>
                        <td>{{ $score_review->anime->median }}</td>
                        <td>{{ $score_review->anime->count }}</td>
                        <td>{{ $score_review->score }}</td>
                        <td>{{ $score_review->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection