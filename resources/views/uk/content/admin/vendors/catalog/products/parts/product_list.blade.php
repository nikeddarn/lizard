<div class="table-responsive">

    <table class="table">

        <thead>
        <tr class="text-center">
            <td><strong>Id</strong></td>
            <td class="d-none d-lg-table-cell"><strong>Изображение</strong></td>
            <td class="d-none d-lg-table-cell"><strong>Артикул</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Страна</strong></td>
            <td><strong>Гарантия</strong></td>
            <td><strong>Цена</strong></td>
            <td><strong>Прибыль ($)</strong></td>
            <td><strong>Прибыль (%)</strong></td>
            <td><strong>Доступность</strong></td>
        </tr>
        </thead>

        <tbody>


        @foreach($vendorCategoryProducts as $vendorOwnProduct)

            <tr class="text-center">

                <td>{{ $vendorOwnProduct->id }}</td>
                <td class="d-none d-lg-table-cell">
                    @if($vendorOwnProduct->image)
                        <img src="{{ $vendorOwnProduct->image }}" class="img-responsive table-image">
                    @endif
                </td>
                <td class="d-none d-lg-table-cell">{{ $vendorOwnProduct->articul }}</td>
                <td>{{ $vendorOwnProduct->name }}</td>
                <td>{{ $vendorOwnProduct->country }}</td>
                <td>{{ $vendorOwnProduct->warranty }}</td>
                <td>{{ $vendorOwnProduct->price }}</td>
                <td>{{ $vendorOwnProduct->profit }}</td>
                <td>{{ $vendorOwnProduct->profitPercents }}</td>
                <td>
                    @if($vendorOwnProduct->isAvailable)
                        <span class="text-success">На складе</span>
                    @elseif($vendorOwnProduct->expected)
                        <span class="text-info">{{ $vendorOwnProduct->expected }}</span>
                    @else
                        <span class="text-danger">Отсутствует</span>
                    @endif
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>
</div>
