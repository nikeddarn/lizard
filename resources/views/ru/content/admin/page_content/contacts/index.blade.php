@extends('layouts.admin')

@section('content')

    @include('content.admin.page_content.contacts.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">

        <form id="main-content-form" method="post"
              action="{{ route('admin.content.contacts.update') }}"
              role="form"
              enctype="multipart/form-data">
            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link show active" data-toggle="tab"
                       href="#content-contacts-content" role="tab"
                       aria-controls="content-contacts-content"
                       aria-selected="true">Содержимое</a>

                    <a class="nav-item nav-link" data-toggle="tab"
                       href="#content-contacts-seo" role="tab"
                       aria-controls="content-contacts-seo"
                       aria-selected="true">Seo</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active"
                     id="content-contacts-content" role="tabpanel"
                     aria-labelledby="content-contacts-content-tab">
                    @include('content.admin.page_content.contacts.parts.content')
                </div>

                <div class="tab-pane fade"
                     id="content-contacts-seo" role="tabpanel"
                     aria-labelledby="content-contacts-seo-tab">
                    @include('content.admin.page_content.contacts.parts.seo')
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>

        </form>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // focus on tab that has error in input field
            let categoryForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="category-form"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
            $(categoryForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(categoryForm);
            });

            // activate admin menu
            let currentLink = $('#main-menu-content-contacts');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

            // activate text editor
            $('.summernote').summernote({
                height: 240,
                popover: {
                    image: [],
                    link: [],
                    air: []
                },
                callbacks: {
                    onImageUpload: function (files) {
                        let editor = this;
                        let data = new FormData();
                        data.append("image", files[0]);
                        $.post({
                            url: "{{ route('admin.content.contacts.upload.image') }}",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: data,
                            success: function (imageUrl) {
                                let image = $('<img>').attr('src', imageUrl);
                                $(editor).summernote("insertNode", image[0]);
                            }
                        })
                    }
                }
            });

        });
    </script>

@endsection
