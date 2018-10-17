@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title">
            <h2>Изменить значение атрибута:<i
                        class="ml-5 admin-content-sub-header">{{ $attributeValue->attribute->name }}</i></h2>
        </div>

        <div class="col-auto admin-content-actions">
            <a href="{{ route('admin.attributes.show', ['id' => $attributeValue->attribute->id]) }}"
               data-toggle="tooltip" title="Назад"
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

        <div class="col-lg-12">
            @include('content.admin.catalog.attribute_value.update.parts.update_form')
        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            let fileInputField = $('.add-image-input').find('input');

            $('#attribute-value-image-delete').click(function () {
                // hide image
                $('#attribute-value-image').removeClass('d-block').addClass('d-none');
                // hide delete button
                $(this).addClass('d-none');
                // clear input file value
                $(fileInputField).val('');
                // set delete image input value
                $('#delete-image-input').val('1');
            });

            $(fileInputField).change(function () {

                let file = this.files[0];
                let reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = function (e) {
                    // show new image
                    $('#attribute-value-image').attr('src', e.target.result).removeClass('d-none').addClass('d-block');
                    // show delete button
                    $('#attribute-value-image-delete').removeClass('d-none').addClass('d-inline-block');
                    // unset delete image input value
                    $('#delete-image-input').val('0');
                };
            });

        });

    </script>

@endsection