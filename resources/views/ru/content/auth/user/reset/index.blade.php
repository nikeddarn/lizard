@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-sm-8">
                <div class="underlined-title">
                    <h3 class="page-header text-gray">Восстановления пароля</h3>
                </div>

                <!-- Reset Password Form -->
                @include('content.auth.user.reset.parts.reset_form')

            </div>

        </div>

    </div>

@endsection