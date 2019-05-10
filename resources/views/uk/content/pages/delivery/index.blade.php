@extends('layouts.common')

@section('meta')

    @if(!empty($pageData['title_uk']))
        <title>{{ $pageData['title_uk'] }}</title>
    @endif

    @if(!empty($pageData['description_uk']))
        <meta name="description" content="{{ $pageData['description_uk'] }}">
    @endif

    @if(!empty($pageData['keywords_uk']))
        <meta name="keywords" content="{{ $pageData['keywords_uk'] }}">
    @endif

@endsection

@section('content')

    <div class="container">

        <div class="card card-body">
            @include('content.pages.delivery.parts.title')

            @include('content.pages.delivery.parts.content')
        </div>

    </div>


@endsection
