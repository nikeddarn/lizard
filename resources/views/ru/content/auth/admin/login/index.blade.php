@extends('layouts.empty')

@section('content')

    <div class="row align-items-center justify-content-md-center" style="height: 100vh">

        <div class="col col-md-6 col-lg-4">

            <div class="underlined-title my-5">
                <h3 class="page-header text-gray-hover"><span class="mr-5">{{ config('app.name') }}</span>Вход
                    администратора</h3>
            </div>

            <div>
                @include('content.auth.admin.login.parts.login_form')
            </div>

        </div>

    </div>

@endsection
