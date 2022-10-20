@extends('layout')

@section('title')
    <title>{{ $user->name }}さんの視聴リタイアしたアニメリスト AnimeScape</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('main')
    <article class="give_up_anime_list">
        <h2>{{ $user->name }}さんの視聴リタイアしたアニメリスト</h2>
        <strong>{{ $user->name }}</strong>
        <h3>視聴リタイアしたアニメリスト</h3>
        <table class="give_up_anime_list_table">
            <tbody>
                <tr>
                    <th>アニメ名</th>
                    <th>制作会社</th>
                    <th>@sortablelink('unionYearCoor', '放送クール')</th>
                    <th>@sortablelink('number_of_episode', '話数')</th>
                    <th>@sortablelink('median', '中央値')</th>
                    <th>@sortablelink('average', '平均値')</th>
                    <th>@sortablelink('stdev', '標準偏差')</th>
                    <th>@sortablelink('count', 'データ数')</th>
                </tr>
                @foreach ($give_up_anime_list as $give_up_anime)
                    <tr>
                        <td><a
                                href="{{ route('anime.show', ['anime_id' => $give_up_anime->id]) }}">{{ $give_up_anime->title }}</a>
                        </td>
                        <td>
                            @foreach ($give_up_anime->companies as $company)
                                <a
                                    href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                            @endforeach
                        </td>
                        <td>
                            {{ $give_up_anime->year }}年{{ $give_up_anime->coor_label }}クール
                        </td>
                        <td>{{ $give_up_anime->number_of_episode }}</td>
                        <td>{{ $give_up_anime->median }}</td>
                        <td>{{ $give_up_anime->average }}</td>
                        <td>{{ $give_up_anime->stdev }}</td>
                        <td>{{ $give_up_anime->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@endsection