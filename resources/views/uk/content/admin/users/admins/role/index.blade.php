@extends('layouts.admin')

@section('content')

    @include('content.admin.users.admins.role.parts.header')

    @include('elements.errors.admin_error.index')

    @if(!$user->roles_count)
        <div class="alert alert-danger my-4">Добавление роли этому пользователю сделает его сотрудником с соответствующими роли правами</div>
    @endif

    <div class="card card-body">
        @include('content.admin.users.admins.role.parts.roles_form')
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            // activate selectpicker
            $('.selectpicker').selectpicker();

        });

    </script>

@endsection
