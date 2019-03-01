@extends('layouts.user')

@section('content')

    <div class="card p-2 my-4">
        <h1 class="h4 text-gray-hover m-0">Фаворитные товары пользователя</h1>
    </div>

    @if(isset($favouriteProducts))

        @if($favouriteProducts->count())
            <div class="card card-body my-4">
                @include('content.user.favourite.registered.parts.list')
            </div>
        @endif

        @if($favouriteProducts->lastPage() !== 1)
            <div class="row">
                <div class="col-12 my-4">
                    @include('layouts.parts.pagination.products.index', ['paginator' => $favouriteProducts])
                </div>
            </div>
        @endif

    @endif

@endsection
