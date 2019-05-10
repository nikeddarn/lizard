@extends('layouts.main')

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
