@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Изменить данные пользователя:<i
                        class="ml-5 admin-content-sub-header">{{ $user->name }}</i></h2></div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="user-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
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

            @include('content.admin.users.update.customers.parts.user_form')

        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $("#price_group").TouchSpin({
                min: 1,
                max: 3,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });
        });

    </script>

@endsection