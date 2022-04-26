@extends('layout')

@section('title')
    <title>{{ $anime->title }}のデータ入力画面 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <h2>{{ $anime->title }}のデータ入力画面</h2>
    <h3><a href="{{ route('anime.show', ['id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
    <div id="score_information">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('anime_review.post', ['id' => $anime->id]) }}" method="POST">
            @csrf
            <label for="will_watch_check">視聴予定:</label>
            <input type="hidden" name="will_watch" value="0">
            <input type="checkbox" name="will_watch" value="1" id="will_watch"
                {{ $my_review->will_watch == 1 ? 'checked' : '' }}>
            <hr>
            <label for="score">得点</label><br>
            <input type="number" size="3" name="score" id="score" value="{{ $my_review->score }}"><br>
            <label for="watch_check">視聴した？:</label>
            <input type="hidden" name="watch" value="0">
            <input type="checkbox" name="watch" value="1" id="watch" {{ $my_review->watch == 1 ? 'checked' : '' }}>

            <hr>
            <label for="one_comment">一言感想</label><br>
            <input type="text" size="100" name="one_word_comment" id="one_comment_form"
                value="{{ $my_review->one_word_comment }}"><br>
            <label for="netabare_check">ネタバレ？:</label>
            <input type="hidden" name="spoiler" value="0">
            <input type="checkbox" name="spoiler" value="1" id="spoiler"
                {{ $my_review->spoiler == 1 ? 'checked' : '' }}><br>
            <input type="submit" value="送信"><br>
        </form>
    </div>
    <h3>注意事項</h3>
    得点は0～100点で付けてください。<br>
    一言感想は400文字以内でお願いします。登録は任意です。
    </div>
    </div>
@endsection
