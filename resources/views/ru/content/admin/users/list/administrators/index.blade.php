@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Сотрудники</h2></div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('content.admin.users.list.administrators.parts.list')
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".admin-delete-form").submit(function (event) {
                if (confirm('Удалить сотрудника ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // activate admin menu
            let currentLink = $('#main-menu-users-admins');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });
        });

    </script>

@endsection
