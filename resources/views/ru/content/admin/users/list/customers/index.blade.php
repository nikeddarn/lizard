@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Пользователи</h2></div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('content.admin.users.list.customers.parts.list')
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".customer-delete-form").submit(function (event) {
                if (confirm('Удалить клиента ?')) {
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