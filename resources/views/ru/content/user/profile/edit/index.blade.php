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

@section('scripts')
    <script type="text/javascript" src="/js/input-file.js"></script>
@endsection

@section('styles')
    <link href="/css/input-file.css" rel="stylesheet">
@endsection