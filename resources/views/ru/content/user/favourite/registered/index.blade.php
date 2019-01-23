@extends('layouts.user')

@section('content')

    <div class="card my-4">
        <div class="card-body">

            <h1 class="h4 text-gray-hover">Фаворитные продукты</h1>

            @include('content.user.favourite.registered.parts.list')

        </div>
    </div>

    @if($favouriteProducts->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $favouriteProducts])
            </div>
        </div>
    @endif

@endsection
