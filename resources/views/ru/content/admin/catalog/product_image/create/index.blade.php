@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title">
            <h2>Добавить изображение к продукту:<i class="ml-5 admin-content-sub-header">{{ $product->name }}</i></h2>
        </div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="product-image-form" data-toggle="tooltip" title="Сохранить"
                    class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-primary"><i class="fa fa-reply"></i></a>
        </div>
    </div>

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-8">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-8">

            <form id="product-image-form" method="post" action="{{ route('admin.products.image.store') }}" role="form"
                  enctype="multipart/form-data">

                @csrf

                <input type="hidden" name="products_id" value="{{ $product->id }}">

                <div class="card  p-5 mb-5">
                    @include('elements.input_image.index')
                </div>

                <button type="submit" class="btn btn-primary">Сохранить изображение</button>

            </form>

        </div>
    </div>

@endsection
