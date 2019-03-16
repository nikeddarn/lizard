@extends('layouts.admin')

@section('content')

    @include('content.admin.page_content.common.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">

        <form id="common-content-form" class="multitab-form" method="post"
              action="{{ route('admin.content.common.update') }}"
              role="form"
              enctype="multipart/form-data">
            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link show active" data-toggle="tab"
                       href="#content-common-header" role="tab"
                       aria-controls="content-common-header"
                       aria-selected="true">Шапка сайта</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active"
                     id="content-common-header" role="tabpanel"
                     aria-labelledby="content-common-header-tab">
                    @include('content.admin.page_content.common.parts.header_content')
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
            let currentLink = $('#main-menu-content-common');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });
    </script>

@endsection
