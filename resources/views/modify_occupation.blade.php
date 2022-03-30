@extends('layout')

@section('title')
    <title>{{ $anime->title }}の出演声優情報変更申請 AnimeScape -アニメ批評空間-</title>
@endsection

@section('main')
    <div id="addForm">
        <div id="modify_anime">
            <h2>{{ $anime->title }}の出演声優情報変更申請</h2>
            <h3><a href="{{ route('anime', ['id' => $anime->id]) }}">{{ $anime->title }}</a></h3>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <form action="{{ route('modify.occupation.post', ['id' => $anime->id]) }}" method="POST">
                @csrf
                <input type="submit" value="登録">
                <table id="modify_occupation_table">
                    <tbody>
                        <tr>
                            <th>声優名</th>
                        </tr>
                        @foreach ($act_casts as $act_cast)
                            <tr>
                                <td><input type="text" name="cast_name_{{ $loop->iteration }}"
                                        value="{{ $act_cast->name }}"></td>
                            </tr>
                        @endforeach
                        <tr v-for="(text,index) in texts">
                            <td>
                                <input type="text" :name="'cast_name_add_'+index" v-model="texts[index]">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" @click="addInput">枠の追加</button>
            </form>
            <h3>注意事項</h3>
            名字と名前の間はスペースなしでお願いします。<br>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('vue.js')
    <script>
        const vue = new Vue({
            el: '#addForm',
            data() {
                return {
                    texts: [],
                };
            },
            methods: {
                addInput() {
                    this.texts.push('');
                },
            },
            mounted() {
                console.log('Component mounted.')
            }
        });
    </script>
@endsection
