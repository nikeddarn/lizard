@extends('layouts.common')

@section('content')

    <div class="container my-3 my-sm-5">

        <div class="row justify-content-center">

            @include('content.auth.user.login.parts.form')

        </div>

    </div>

@endsection
