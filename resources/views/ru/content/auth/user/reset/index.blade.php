@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="row page-content justify-content-center">

            <div class="col-sm-8">

                <div class="row">

                    <div class="col-lg-12">
                        <div class="underlined-title">
                            <h3 class="page-header text-gray">Восстановления пароля</h3>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <!-- Reset Password Form -->
                        @include('content.auth.user.reset.parts.reset_form')
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection