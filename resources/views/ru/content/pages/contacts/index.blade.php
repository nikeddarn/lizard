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
            @include('content.pages.contacts.parts.title')

            @include('content.pages.contacts.parts.storages')

            @include('content.pages.contacts.parts.content')
        </div>

    </div>


@endsection

@section('scripts')



    <script>
        function initMap() {
            $('.storage-card').each(function () {

                let coordinates = {
                    lat: parseFloat($(this).data('lat')),
                    lng: parseFloat($(this).data('lon'))
                };

                let map = new google.maps.Map($(this).find('.contact-map').get(0), {
                    zoom: 16,
                    center: coordinates
                });

                let marker = new google.maps.Marker({
                    position: coordinates,
                    map: map
                });

                infowindow = new google.maps.InfoWindow({
                    content: 'Интернет магазин Lizard'
                });

                infowindow.open(map, marker);

                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            });
        }
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATXenqDXHU4rnQmKfQ55ocnFYR1S2F4EA&callback=initMap"></script>

@endsection
