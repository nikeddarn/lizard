@if($product->vendors->count())

    <div class="table-responsive">
        <table class="table">

            <thead>
            <tr>
                <td><strong>Поставщик</strong></td>
            </tr>
            </thead>

            <tbody>

            @foreach($product->vendors as $vendor)
                <tr>
                    <td>{{ $vendor->name_ru }}</td>
                </tr>
            @endforeach

            </tbody>

        </table>
    </div>

@endif
