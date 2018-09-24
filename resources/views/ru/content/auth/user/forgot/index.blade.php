@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="row page-content justify-content-center">

            <div class="col-sm-8">
                <div class="underlined-title">
                    <h3 class="page-header text-gray">Запрос на восстановления пароля</h3>
                </div>
            </div>

            <div class="col-sm-8">
                <!-- Forgot Form -->
                @include('content.auth.user.forgot.parts.forgot_form')

            </div>

        </div>

    </div>

@endsection