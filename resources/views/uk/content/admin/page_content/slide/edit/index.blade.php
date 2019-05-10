@extends('layouts.admin')

@section('content')

    @include('content.admin.page_content.slide.edit.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">

        <form id="slide-form" class="multitab-form" method="post" action="{{ route('admin.slider.slide.update') }}"
              role="form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="slide_id" value="{{ $slide->id }}">

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#slide-image" role="tab"
                       aria-controls="slide-image" aria-selected="true">Изображения</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#slide-title" role="tab"
                       aria-controls="slide-title" aria-selected="false">Подписи</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#slide-link" role="tab"
                       aria-controls="slide-link" aria-selected="false">Ссылки</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="slide-image" role="tabpanel"
                     aria-labelledby="slide-image-tab">
                    @include('content.admin.page_content.slide.edit.parts.images')
                </div>
                <div class="tab-pane fade" id="slide-title" role="tabpanel" aria-labelledby="slide-title-tab">
                    @include('content.admin.page_content.slide.edit.parts.titles')
                </div>
                <div class="tab-pane fade" id="slide-link" role="tabpanel" aria-labelledby="slide-link-tab">
                    @include('content.admin.page_content.slide.edit.parts.links')
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить слайд</button>

        </form>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="{{ url('/js/generate-url.js') }}"></script>

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

        });

    </script>

@endsection
