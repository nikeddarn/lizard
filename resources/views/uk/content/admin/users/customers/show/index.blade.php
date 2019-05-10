@extends('layouts.admin')

@section('content')

    @include('content.admin.users.customers.show.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <a class="nav-item nav-link show active" data-toggle="tab"
                   href="#statistics" role="tab"
                   aria-controls="statistics"
                   aria-selected="false">Статистика</a>
                <a class="nav-item nav-link" data-toggle="tab"
                   href="#properties" role="tab"
                   aria-controls="properties"
                   aria-selected="true">Личные данные</a>

            </div>
        </nav>

        <div class="tab-content">

            <div class="tab-pane fade show active"
                 id="statistics" role="tabpanel"
                 aria-labelledby="statistics-tab">
                @include('content.admin.users.customers.show.parts.statistics')
            </div>
            <div class="tab-pane fade"
                 id="properties" role="tabpanel"
                 aria-labelledby="properties-tab">
                @include('content.admin.users.customers.show.parts.properties')
            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".customer-delete-form").submit(function (event) {
                if (confirm('Удалить пользователя ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".make-employee").click(function (event) {
                if (confirm('Создать сотрудника из этого пользователя ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // increase user price group
            $('.increase-price-group').submit(function (event) {
                if (confirm('Увеличить скидку (прайс колонку) для пользователя ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function (data) {
                            // update price group value
                            $('.user-price-group').html(data);

                            let priceGroup = parseInt(data);

                            // show decrease button
                            if (priceGroup === 2) {
                                $(form).closest('.user-actions').find('.decrease-price-group').removeClass('d-none').addClass('d-inline-block');
                            }
                            // hide increase button
                            if (priceGroup === 3) {
                                $(form).removeClass('d-inline-block').addClass('d-none');
                            }
                        }
                    });
                }
                event.preventDefault();

                $(this).find('button').blur();

                return false;
            });

            // decrease user price group
            $('.decrease-price-group').submit(function (event) {
                if (confirm('Уменьшить скидку (прайс колонку) для пользователя ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function (data) {
                            // update price group value
                            $('.user-price-group').html(data);

                            let priceGroup = parseInt(data);

                            // show increase button
                            if (priceGroup === 2) {
                                $(form).closest('.user-actions').find('.increase-price-group').removeClass('d-none').addClass('d-inline-block');
                            }
                            // hide decrease button
                            if (priceGroup === 1) {
                                $(form).removeClass('d-inline-block').addClass('d-none');
                            }
                        }
                    });
                }
                event.preventDefault();

                $(this).find('button').blur();

                return false;
            });

        });

    </script>

@endsection
