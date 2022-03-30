@extends('layout')

@section('title')
    <title>{{ $user->uid }}さんの視聴予定表 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>{{ $user->uid }}さんの視聴予定表</h2>
    <strong>{{ $user->uid }}</strong>
    <h3>視聴予定表</h3>
    <div id="will_watch">
        <table id="will_watch_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>ブランド名</th>
                    <th>放送クール</th>
                    <th>中央値</th>
                    <th>データ数</th>
                </tr>
                @foreach ($user_reviews as $user_review)
                    <tr>
                        <td><a
                                href="{{ route('anime', ['id' => $user_review->anime->id]) }}">{{ $user_review->anime->title }}</a>
                        </td>
                        <td>{{ $user_review->anime->company }}</td>
                        <td>
                            {{ $user_review->anime->year }}年{{ $user_review->anime->coor_label }}クール
                        </td>
                        <td>{{ $user_review->anime->median }}</td>
                        <td>{{ $user_review->anime->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection
