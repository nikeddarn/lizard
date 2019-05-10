@extends('layouts.product')

@section('content')

    <div class="card p-2 my-4">
        <h1 class="h5 text-gray-hover m-0">{{ $category->name }}</h1>
    </div>

    {{--<div class="card card-body my-4">--}}
        @include('content.shop.category.categories_list.parts.list')
    {{--</div>--}}

    @if(!empty($category->content))
        <div class="card card-body my-4">
            <div class="col-lg-12 my-5">{!! $category->content !!}</div>
        </div>
    @endif

@endsection
