@extends('layouts.empty')

@section('content')

    <div class="container-fluid">

        <div class="row justify-content-center align-items-center" style="height: 100vh">

            <div class="col-auto">

                @if ($errors->any())

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                @else

                    <h1 class="text-center mb-5">Setup Complete</h1>
                    <h3>
                        Don't forget remove this route from list
                    </h3>

                @endif

            </div>

        </div>

    </div>

@endsection