@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title">
            <h2>Добавить роль пользователю:<i class="ml-5 admin-content-sub-header">{{ $user->name }}</i></h2>
        </div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="user-role-form" data-toggle="tooltip" title="Сохранить"
                    class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ url()->previous() }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-primary"><i class="fa fa-reply"></i></a>
        </div>
    </div>

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-8">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">

            @include('content.admin.users.roles.create.parts.roles_form')

        </div>
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