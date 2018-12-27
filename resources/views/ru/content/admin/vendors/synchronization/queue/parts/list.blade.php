<table class="table table-bordered table-striped m-0">

    <thead>
    <tr class="text-center">
        <td><strong>Поставщик</strong></td>
        <td><strong>Вставка новых товаров (последняя синхронизация)</strong></td>
        <td><strong>Обновление товаров (последняя синхронизация)</strong></td>
        <td><strong>Действия</strong></td>
    </tr>
    </thead>

    <tbody>

    @foreach($vendors as $vendor)

        <tr class="text-center">
            <td>{{ $vendor->name_ru }}</td>

            @if($vendor->sync_new_products_at)
                <td>{{ $vendor->{\App\Contracts\Vendor\SyncTypeInterface::INSERT_PRODUCT . '_count'} }}&emsp;({{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vendor->sync_new_products_at)->diffForHumans() }})</td>
            @else
                <td>Не синхронизировалось</td>
            @endif

            @if($vendor->sync_prices_at)
                <td>{{ $vendor->{\App\Contracts\Vendor\SyncTypeInterface::UPDATE_PRODUCT . '_count'} }}&emsp;({{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vendor->sync_prices_at)->diffForHumans() }})</td>
            @else
                <td>Не синхронизировалось</td>
            @endif

            <td class="py-0 sync-category-actions">

                <a href="{{ route('vendor.synchronization.synchronize', ['vendorId' => $vendor->id]) }}"
                   data-toggle="tooltip"
                   title="Синхронизировать поставщика {{ $vendor->name_ru }}" class="btn btn-primary">
                    <i class="fa fa-refresh"></i>
                </a>

            </td>
        </tr>

    @endforeach

    </tbody>
</table>
