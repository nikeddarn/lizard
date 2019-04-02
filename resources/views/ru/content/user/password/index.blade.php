@extends('layouts.user')

@section('content')

    <div class="card card-body my-4">

        <h1 class="h3 text-gray-hover m-0">Изменение пароля</h1>

        <hr>

        @include('content.user.password.parts.change_password_form')
    </div>

@endsection
