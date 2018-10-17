@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title">
            <h2>Атрибут:<i class="ml-5 admin-content-sub-header">{{ $attribute->name }}</i></h2>
        </div>

        <div class="col-auto admin-content-actions">

            <a href="{{ route('admin.attributes.edit', ['id' => $attribute->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

            <form class="d-inline-block attribute-form"
                  action="{{ route('admin.attributes.destroy', ['id' => $attribute->id]) }}" method="post">
                @csrf
                <input type="hidden" name="_method" value="delete"/>
                <input type="hidden" name="id" value="{{ $attribute->id }}">
                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                    <i class="fa fa-trash-o"></i>
                </button>
            </form>

            <a href="{{ route('admin.attributes.index') }}" data-toggle="tooltip" title="К списку атрибутов"
               class="btn btn-primary ml-lg-2"><i class="fa fa-reply"></i></a>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-12">
            @include('content.admin.catalog.attribute.show.parts.properties')
        </div>

        <div class="col-lg-12">
            @if($haveValuesImages)
                @include('content.admin.catalog.attribute.show.parts.imaged_attribute_options')
            @else
                @include('content.admin.catalog.attribute.show.parts.attribute_options')
            @endif
        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".delete-item-form").submit(function (event) {
                if (confirm('Удалить значение атрибута ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".attribute-form").submit(function (event) {
                if (confirm('Удалить атрибут ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection