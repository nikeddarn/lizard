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

    <div class="row">
        <div class="col-lg-12 my-5">{!! $category->content !!}</div>
    </div>

@endsection

@section('breadcrumbs')

    <div class="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-2">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    @foreach($breadcrumbs as $name => $href)
                        @if ($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ $href }}">{{ $name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

@endsection