@extends('layouts.user')

@section('content')

    <section class="row page-content">

        <div class="col-lg-12">
            <div class="underlined-title">
                <h3 class="page-header text-gray">Фаворитные продукты</h3>
            </div>
        </div>

        <div class="col-lg-12">
            @include('content.user.favourite.registered.parts.list')
        </div>
    </section>

@endsection