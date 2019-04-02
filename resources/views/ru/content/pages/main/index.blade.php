@extends('layouts.main')

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

    <div class="container bg-white pt-4">

        <div id="left-menu-row" class="row mb-4">

            <div class="col-lg-3 d-none d-lg-block">
                @include('content.pages.main.parts.catalog')
            </div>

            <div class="col-12 col-lg-9">
                @if($mainSlider->slides->count())
                    @include('content.pages.main.parts.slider')
                @endif
            </div>

        </div>

        @if($productGroups->count())
            @include('content.pages.main.parts.products')
        @endif

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // show all products of group
            $('.show-all-products-button').click(function () {
                $(this).closest('.products-group').find('.row').css('flex-wrap', 'wrap');
                $(this).remove();
            });

        });

    </script>

@endsection
