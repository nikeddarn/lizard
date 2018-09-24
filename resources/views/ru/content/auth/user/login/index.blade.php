@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="row page-content">

            <div class="col-sm-4">
                <div class="underlined-title">
                    <h3 class="page-header text-gray">Вход пользователя</h3>
                </div>

                <!-- Login Form -->
                @include('content.auth.user.login.parts.login_form')

            </div>

            <div class="col-sm-8">
                <div class="underlined-title">
                    <h3 class="page-header text-gray">Нет аккаунта? Зарегистрируйтесь</h3>
                </div>

                <!-- Registration Form -->
                @include('content.auth.user.login.parts.registration_form')

            </div>

        </div>

    </div>

@endsection