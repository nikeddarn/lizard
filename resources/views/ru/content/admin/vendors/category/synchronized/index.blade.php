@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title"><h2>Синхронизированные категории</h2></div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            @if($synchronizedCategories->count())

                @include('content.admin.vendors.category.synchronized.parts.categories')

                @if($synchronizedCategories->links())
                    <div class="col-lg-12 my-4">{{$synchronizedCategories->links()}}</div>
                @endif

            @else
                <p>Нет синхронизированных категорий</p>
            @endif

        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {


            // confirm unlink category
            $(".unlink-form").submit(function (event) {
                if (confirm('Отвязать категорию поставщика и удалить все связанные товары ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // turn off auto download products of synchronized categories
            $('.auto-add-products-off-form').submit(function (event) {
                if (confirm('Выключить автодобавление новых продуктов ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function () {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.sync-category-actions').find('.auto-add-products-on-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

            //turn on auto download products of synchronized categories
            $('.auto-add-products-on-form').click(function (event) {
                if (confirm('Включить автодобавление новых продуктов ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function () {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.sync-category-actions').find('.auto-add-products-off-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

        });

    </script>

@endsection
