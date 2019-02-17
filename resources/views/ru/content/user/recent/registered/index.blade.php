@extends('layouts.user')

@section('content')

    <div class="card p-2 my-4">
        <h1 class="h4 text-gray-hover m-0">Недавно просмотренные товары</h1>
    </div>

    @if($recentProducts->count())
        <div class="card card-body my-4">
            @include('content.user.recent.registered.parts.list')
        </div>
    @endif

    @if($recentProducts->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $recentProducts])
            </div>
        </div>
    @endif

@endsection
