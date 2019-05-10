@extends('layouts.admin')

@section('content')

    @include('content.admin.users.admins.list.parts.header')

    @include('elements.errors.admin_error.index')

    @if($users->count())
        <div class="card card-body">
            @include('content.admin.users.admins.list.parts.list')
        </div>
    @endif

    @if($users->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $users])
            </div>
        </div>
    @endif

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
