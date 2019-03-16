@extends('layouts.common')

@section('content')

    @if(!empty($mainSlider))
        @include('content.pages.main.parts.slider')
    @endif

    @if(!empty($productGroups))
        <div class="container pt-4 bg-white">
            @include('content.pages.main.parts.products')
        </div>
    @endif

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
