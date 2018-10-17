@extends('layouts.user')

@section('content')

    <section class="row page-content">

        <div class="col-lg-12">
            <div class="underlined-title">
                <h3 class="page-header text-gray">Редактирование личных данных</h3>
            </div>
        </div>

        <div class="col-lg-12">
            @include('content.user.profile.edit.parts.profile_form')
        </div>
    </section>

@endsection