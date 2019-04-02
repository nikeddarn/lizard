@extends('layouts.user')

@section('content')

    <div class="card card-body my-4">

        <h1 class="h3 text-gray-hover m-0">Фаворитные товары</h1>

        <hr>

        @if(isset($favouriteProducts) && $favouriteProducts->count())
            @include('content.user.favourite.registered.parts.list')
        @endif

    </div>

    @if(isset($favouriteProducts) && $favouriteProducts->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $favouriteProducts])
            </div>
        </div>
    @endif

@endsection
