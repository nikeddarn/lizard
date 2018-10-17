@extends('layouts.shop')

@section('content')

    <div class="row">

        <div class="col-lg-12 my-4">
            <div class="underlined-title">
                <h1 class="text-gray px-2">{{ $category->name }}</h1>
            </div>
        </div>

        <div class="col-lg-12 my-4">
            @include('content.shop.category.categories_list.parts.list')
        </div>
    </div>

@endsection