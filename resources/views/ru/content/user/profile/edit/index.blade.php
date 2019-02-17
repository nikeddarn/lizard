@extends('layouts.user')

@section('content')

    <div class="card p-2 my-4">
        <h1 class="h4 text-gray-hover m-0">Редактирование личных данных</h1>
    </div>

    <div class="card card-body my-4">
        @include('content.user.profile.edit.parts.profile_form')
    </div>

@endsection
