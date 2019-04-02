@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="card card-body my-4">

            <h1 class="h3 text-gray-hover m-0">Недавно просмотренные товары</h1>

            <hr>

            @if(isset($recentProducts) && $recentProducts->count())
                @include('content.user.recent.unregistered.parts.list')
            @endif

        </div>

        @if(isset($recentProducts) && $recentProducts->lastPage() !== 1)
            <div class="row">
                <div class="col-12 my-4">
                    @include('layouts.parts.pagination.products.index', ['paginator' => $recentProducts])
                </div>
            </div>
        @endif

    </div>

@endsection
