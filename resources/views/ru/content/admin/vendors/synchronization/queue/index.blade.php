@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title"><h2>Задачи в очереди синхронизации</h2></div>
    </div>

    <div class="row">
        <div id="vendor-synchronization-list" class="col-lg-12">

            @if($vendors->count())

                @include('content.admin.vendors.synchronization.queue.parts.list')

            @else
                <p>Нет синхронизированных поставщиков</p>
            @endif

        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // auto update sync data
            setInterval(function () {
                $.ajax({
                    url: '{{ route('vendor.synchronization.index') }}',
                    success: function(html){
                        $('#vendor-synchronization-list').html(html);
                    }
                });
            }, 10000);

            // activate admin menu
            let currentLink = $('#main-menu-sync-jobs');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
