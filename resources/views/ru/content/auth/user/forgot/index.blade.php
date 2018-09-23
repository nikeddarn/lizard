@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-sm-8">
                <div class="underlined-title">
                    <h3 class="page-header text-gray">Запрос на восстановления пароля</h3>
                </div>

                <!-- Forgot Form -->
                @include('content.auth.user.forgot.parts.forgot_form')

            </div>

        </div>

    </div>

@endsection