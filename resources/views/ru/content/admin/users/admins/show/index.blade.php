@extends('layouts.admin')

@section('content')

    @include('content.admin.users.admins.show.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <a class="nav-item nav-link show active" data-toggle="tab"
                   href="#statistics" role="tab"
                   aria-controls="statistics"
                   aria-selected="false">Статистика</a>
                <a class="nav-item nav-link" data-toggle="tab"
                   href="#admin-roles" role="tab"
                   aria-controls="admin-roles"
                   aria-selected="true">Роли</a>
                <a class="nav-item nav-link" data-toggle="tab"
                   href="#properties" role="tab"
                   aria-controls="properties"
                   aria-selected="true">Личные данные</a>

            </div>
        </nav>

        <div class="tab-content">

            <div class="tab-pane fade show active"
                 id="statistics" role="tabpanel"
                 aria-labelledby="statistics-tab">
                @include('content.admin.users.admins.show.parts.statistics')
            </div>
            <div class="tab-pane fade"
                 id="admin-roles" role="tabpanel"
                 aria-labelledby="admin-roles-tab">
                @include('content.admin.users.admins.show.parts.roles')
            </div>
            <div class="tab-pane fade"
                 id="properties" role="tabpanel"
                 aria-labelledby="properties-tab">
                @include('content.admin.users.admins.show.parts.properties')
            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".customer-delete-form").submit(function (event) {
                if (confirm('Удалить пользователя ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $('.user-role-delete-form').submit(function (event) {

                if (parseInt($(this).data('roles-count')) === 1) {
                    if (confirm('Удаление единственной роли переведет сотрудника в разряд пользователей. Продолжить ?')) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;
                    }
                } else {
                    if (confirm('Удалить роль пользователя ?')) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;
                    }
                }
            });

        });

    </script>

@endsection
