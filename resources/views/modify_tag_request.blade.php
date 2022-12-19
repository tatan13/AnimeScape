@extends('layout')

@section('title')
    <title>{{ $tag->name }}の情報変更申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="modify_tag_request">
        <h1>{{ $tag->name }}の情報変更申請</h1>
        <h2><a href="{{ route('tag.show', ['tag_id' => $tag->id]) }}">{{ $tag->name }}</a></h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        <form action="{{ route('modify_tag_request.post', ['tag_id' => $tag->id]) }}" class="modify_tag_request_form"
            method="POST">
            @csrf
            <input type="submit" value="送信">
            <div class="table-responsive">
                <table class="modify_tag_request_table">
                    <tbody>
                        <tr>
                            <th>項目</th>
                            <th>現在の情報</th>
                            <th>訂正情報</th>
                        </tr>
                        <tr>
                            <th>名前</th>
                            <td>{{ $tag->name }}</td>
                            <td><input type="text" name="name" value="{{ $tag->name }}"></td>
                        </tr>
                        <tr>
                            <th>タググループ</th>
                            <td>{{ $tag->tag_group_id_label }}</td>
                            <td>
                                <select name="tag_group_id">
                                    <option value="{{ \App\Models\Tag::TYPE_ORIGINAL }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_ORIGINAL ? 'selected' : ''}}>
                                        原作
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_GENRE }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_GENRE ? 'selected' : ''}}>
                                        ジャンル
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_CHARACTER }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_CHARACTER ? 'selected' : ''}}>
                                        キャラクター
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_STORY }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_STORY ? 'selected' : ''}}>
                                        ストーリー
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_MUSIC }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_MUSIC ? 'selected' : ''}}>
                                        音
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_PICTURE }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_PICTURE ? 'selected' : ''}}>
                                        作画
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_CAST }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_CAST ? 'selected' : ''}}>
                                        声優
                                    </option>
                                    <option value="{{ \App\Models\Tag::TYPE_OTHER }}" {{ $tag->tag_group_id == \App\Models\Tag::TYPE_OTHER ? 'selected' : ''}}>
                                        その他
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>ネタバレ？</th>
                            <td>{{ $tag->spoiler == true ? '○' : '' }}</td>
                            <td>
                                <input type="hidden" name="spoiler" value="0">
                                <input type="checkbox" name="spoiler" value="1"
                                    {{ $tag->spoiler == true ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr>
                            <th>説明</th>
                            <td>{{ $tag->explanation }}</td>
                            <td>
                                <input type="text" name="explanation" value="{{ $tag->explanation }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </article>
@endsection
