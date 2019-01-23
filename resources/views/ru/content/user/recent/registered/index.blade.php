@extends('layouts.user')

@section('content')

    <div class="card my-4">
        <div class="card-body">

            <h1 class="h4 text-gray-hover">Недавние продукты</h1>

            @include('content.user.recent.registered.parts.list')

        </div>
    </div>

    @if($recentProducts->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $recentProducts])
            </div>
        </div>
    @endif

@endsection
