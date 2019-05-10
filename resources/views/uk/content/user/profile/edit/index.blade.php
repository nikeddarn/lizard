@extends('layouts.user')

@section('content')

    <div class="card card-body my-4">

        <h1 class="h3 text-gray-hover m-0">Мій профіль</h1>

        <hr>

        @include('content.user.profile.edit.parts.profile_form')
    </div>

@endsection
