@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.attribute_value.update.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.catalog.attribute_value.update.parts.update_form')
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
