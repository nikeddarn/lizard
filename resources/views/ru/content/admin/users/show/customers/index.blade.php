@extends('layouts.admin')

@section('content')

    <div class="row justify-content-between admin-content-header">
        <div class="col admin-content-title"><h2>Пользователь:<i
                        class="ml-5 admin-content-sub-header">{{ $user->name }}</i></h2></div>
        <div class="col-auto admin-content-actions">

            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

            @can('create', \App\Models\UserRole::class)
                <a href="{{ route('admin.users.role.create', ['id' => $user->id]) }}" data-toggle="tooltip"
                   title="Создать сотрудника" class="btn btn-primary make-employee"><i class="fa fa-id-badge"></i></a>
            @endcan

            <form class="customer-delete-form d-inline-block"
                  action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                    <i class="fa fa-trash-o"></i>
                </button>
            </form>

            <a href="{{ route('admin.users.administrators') }}" data-toggle="tooltip" title="К списку пользователей"
               class="btn btn-primary ml-lg-2"><i class="fa fa-reply"></i></a>

        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            @include('content.admin.users.show.customers.parts.properties')
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

            $(".make-employee").click(function (event) {
                if (confirm('Создать сотрудника из этого пользователя ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection