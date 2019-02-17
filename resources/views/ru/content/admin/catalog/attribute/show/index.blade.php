@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.attribute.show.parts.header')

    <div class="card card-body my-4">

        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" id="properties-tab" data-toggle="tab" href="#properties" role="tab"
                   aria-controls="properties"
                   aria-selected="true">Свойства</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="values-tab" data-toggle="tab" href="#values" role="tab"
                   aria-controls="values" aria-selected="false">Значения атрибута</a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="properties" role="tabpanel" aria-labelledby="properties-tab">
                @include('content.admin.catalog.attribute.show.parts.properties')
            </div>

            <div class="tab-pane fade" id="values" role="tabpanel" aria-labelledby="values-tab">
                @if($attributeValues->count())
                    @include('content.admin.catalog.attribute.show.parts.attribute_values')
                @endif
            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".delete-item-form").submit(function (event) {
                if (confirm('Удалить значение атрибута ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".attribute-form").submit(function (event) {
                if (confirm('Удалить атрибут ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection
