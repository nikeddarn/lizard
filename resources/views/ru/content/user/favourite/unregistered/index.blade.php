@extends('layouts.common')

@section('content')

    <div class="container">

    <section class="row page-content">

        <div class="col-12 col-lg-8">
            <div class="underlined-title">
                <h3 class="page-header text-gray">Фаворитные продукты</h3>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            @include('content.user.favourite.unregistered.parts.list')
        </div>
    </section>

    </div>

@endsection