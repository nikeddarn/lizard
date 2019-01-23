@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="row my-5">

            <div class="col-sm-4">
                <div class="underlined-title mb-4">
                    <h3 class="page-header text-gray">Вход пользователя</h3>
                </div>

                <!-- Login Form -->
                @include('content.auth.user.login.parts.login_form')

            </div>

            <div class="col-sm-8">
                <div class="underlined-title mb-4">
                    <h3 class="page-header text-gray">Нет аккаунта? Зарегистрируйтесь</h3>
                </div>

                <!-- Registration Form -->
                @include('content.auth.user.login.parts.registration_form')

            </div>

        </div>

    </div>

@endsection
