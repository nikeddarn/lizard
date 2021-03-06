@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Бренды продуктов</h2></div>
        <div class="col-auto admin-content-actions">
            <a class="btn btn-primary" href="{{ route('admin.brands.create') }}" data-toggle="tooltip"
               title="Создать бренд">
                <i class="fa fa-plus"></i>&nbsp;
                <span>Создать бренд</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('content.admin.catalog.brand.list.parts.brands_list')
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".brand-form").submit(function (event) {
                if (confirm('Удалить бренд ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });
    </script>

@endsection