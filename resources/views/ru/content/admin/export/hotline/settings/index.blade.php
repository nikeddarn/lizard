@extends('layouts.admin')

@section('content')

    @include('content.admin.export.hotline.settings.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">

        <form id="settings-form" class="multitab-form" method="post"
              action="{{ route('admin.export.hotline.settings.store') }}" role="form">

            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#settings-hotline-common" role="tab"
                       aria-controls="settings-hotline-common" aria-selected="true">Общие</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="settings-hotline-common" role="tabpanel"
                     aria-labelledby="settings-hotline-common-tab">
                    @include('content.admin.export.hotline.settings.parts.common')
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
            let currentLink = $('#main-menu-export-hotline-settings');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });
    </script>

@endsection
