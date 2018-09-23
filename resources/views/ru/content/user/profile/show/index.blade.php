@extends('layouts.user')

@section('content')

    <section class="row page-content">

        <div class="col-lg-12">
            <div class="underlined-title">
                <h3 class="page-header text-gray">Личные данные пользователя</h3>
            </div>
        </div>

        <div class="col-lg-12">
            @include('content.user.profile.show.parts.profile_data')
        </div>
    </section>

@endsection