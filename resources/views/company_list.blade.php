@extends('layout')

@section('title')
    <title>制作会社リスト AnimeScape -アニメ批評空間-</title>
@endsection

@section('adsense')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1583558174306860"
        crossorigin="anonymous"></script>
@endsection

@section('Breadcrumbs')
    {{ Breadcrumbs::render('company_list') }}
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
    <article class="company_list">
        <h1>制作会社リスト</h1>
        <div class="table-responsive">
            <section class="company_list">
                <table class="company_list_table">
                    <tbody>
                        <tr>
                            <th>制作会社ID</th>
                            <th>制作会社名</th>
                        </tr>
                        @foreach ($company_all as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td><a href="{{ route('company.show', ['company_id' => $company->id]) }}">{{ $company->name }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
        </section>
    </article>
@endsection
