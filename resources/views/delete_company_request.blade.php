@extends('layout')

@section('title')
    <title>{{ $company->name }}の削除申請 AnimeScape -アニメ批評空間-</title>
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('main')
    <article class="delete_company_request">
        <h2>{{ $company->name }}の削除申請</h2>
        <h3><a href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a></h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('delete_company_request.post', ['company_id' => $company->id]) }}" class="delete_company_request_form" method="POST">
            @csrf
            <label for="remark">削除事由</label><br>
            <input type="text" size="100" name="remark" class="remark"
                value=""><br>
            <input type="submit" value="送信"><br>
        </form>
        <h3>注意事項</h3>
        削除事由は400文字以内で入力してください。
    </article>
@endsection