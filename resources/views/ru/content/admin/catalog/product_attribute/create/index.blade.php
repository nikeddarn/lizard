@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title">
            <h2>Добавить атрибут к продукту:<i class="ml-5 admin-content-sub-header">{{ $product->name }}</i></h2>
        </div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="product-attribute-form" data-toggle="tooltip" title="Сохранить"
                    class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-primary"><i class="fa fa-reply"></i></a>
        </div>
    </div>

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-12">
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
        <div class="col-lg-12">

            @include('content.admin.catalog.product_attribute.create.parts.attribute_form')

        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            let attributeSelect = $('.attribute-id-select');
            let attributeValueSelect = $('.attribute-value-id-select');

            // activate selectpicker
            $(attributeSelect).add(attributeValueSelect).addClass('selectpicker').selectpicker();

            // add attribute values options on attribute selected
            $(attributeSelect).change(function (e) {
                e.stopImmediatePropagation();

                let attributeValues = $(this).find('option:selected').data('attribute-values');
                let attributeValueSelect = $(this).closest('.product-attribute-item').find('select.attribute-value-id-select');

                // clear old option elements
                if ($(attributeValueSelect).hasClass('selectpicker')) {
                    $(attributeValueSelect).empty();
                }

                // create new option elements
                $.each(attributeValues, function (index, attributeValue) {
                    let optionElement = $('<option/>').attr('value', attributeValue.id).text(attributeValue.name);
                    $(attributeValueSelect).append(optionElement);
                });

                // activate selectpicker
                $(attributeValueSelect).addClass('selectpicker').selectpicker('refresh');

                // show select attribute values block
                $(attributeValueSelect).closest('.row').removeClass('d-none');

                // remove start select option
                $(this).find('option[value="0"]').remove();
                $(this).selectpicker('refresh');
            })

        });

    </script>

@endsection