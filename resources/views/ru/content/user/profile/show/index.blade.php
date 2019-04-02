@extends('layouts.user')

@section('content')

    <div class="card card-body my-4">

        <h1 class="h3 text-gray-hover m-0">Личные данные пользователя</h1>

        <hr>

        @include('content.user.profile.show.parts.profile_data')

    </div>

@endsection
