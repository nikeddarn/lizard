@extends('layouts.admin')

@section('content')

    @include('content.admin.users.customers.list.parts.header')

    @include('elements.errors.admin_error.index')

    @if($users->count())
        <div class="card card-body">
            @include('content.admin.users.customers.list.parts.list')
        </div>
    @endif

    @if($users->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $users])
            </div>
        </div>
    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".customer-delete-form").submit(function (event) {
                if (confirm('Удалить клиента ?')) {
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

            // activate admin menu
            let currentLink = $('#main-menu-users-customers');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
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
                            $(form).closest('tr').find('.user-price-group').html(data);

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
                            $(form).closest('tr').find('.user-price-group').html(data);

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
