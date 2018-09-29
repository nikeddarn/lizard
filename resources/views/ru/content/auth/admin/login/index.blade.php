@extends('layouts.empty')

@section('content')

    <div class="row align-items-center justify-content-md-center" style="height: 100vh">

        <div class="col-md-6 col-lg-4 page-content">

            <div class="row">

                <div class="col-lg-12">
                    <div class="underlined-title">
                        <h3 class="page-header text-gray"><span class="mr-5">{{ config('app.name') }}</span>Вход
                            администратора</h3>
                    </div>
                </div>

                <div class="col-lg-12">
                    @include('content.auth.admin.login.parts.login_form')
                </div>

            </div>

        </div>

    </div>

@endsection