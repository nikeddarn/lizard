@extends('layouts.admin')

@section('content')

    <div class="row justify-content-between admin-content-header">
        <div class="col admin-content-title"><h2>Сотрудник:<i
                        class="ml-5 admin-content-sub-header">{{ $user->name }}</i></h2></div>
        <div class="col-auto admin-content-actions">

            @can('delete', $user)
                <form class="admin-delete-form d-inline-block"
                      action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </form>
            @endcan

            <a href="{{ route('admin.users.administrators') }}" data-toggle="tooltip" title="К списку сотрудников"
               class="btn btn-primary ml-lg-2"><i class="fa fa-reply"></i></a>

        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            @include('content.admin.users.show.administrators.parts.properties')
        </div>

        <div class="col-lg-12">
            @include('content.admin.users.show.administrators.parts.roles')
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

            // delete user role
            let deleteUserRoleForms = $(".user-role-delete-form");

            $(deleteUserRoleForms).submit(function (event) {

                if (deleteUserRoleForms.length === 1) {
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