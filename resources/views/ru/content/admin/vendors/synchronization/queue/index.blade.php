@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.synchronization.queue.parts.header')

    @include('elements.errors.admin_error.index')

    @if($vendors->count())
        <div id="vendor-synchronization-list" class="card card-body">
            @include('content.admin.vendors.synchronization.queue.parts.list')
        </div>
    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // auto update sync data
            setInterval(function () {
                $.ajax({
                    url: '{{ route('vendor.synchronization.index') }}',
                    success: function (html) {
                        $('#vendor-synchronization-list').html(html);
                        feather.replace();
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
