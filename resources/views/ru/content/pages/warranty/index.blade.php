@extends('layouts.common')

@section('meta')

    @if(!empty($pageData['title_ru']))
        <title>{{ $pageData['title_ru'] }}</title>
    @endif

    @if(!empty($pageData['description_ru']))
        <meta name="description" content="{{ $pageData['description_ru'] }}">
    @endif

    @if(!empty($pageData['keywords_ru']))
        <meta name="keywords" content="{{ $pageData['keywords_ru'] }}">
    @endif

@endsection

@section('content')

    <div class="container">

        <div class="card card-body">
            @include('content.pages.warranty.parts.title')

            @include('content.pages.warranty.parts.content')
        </div>

    </div>


@endsection
