@if($product->attributeValues->count())
    <table id="product-attributes-table" class="table table-bordered text-gray-hover">
        <tbody>

        @foreach($productAttributes as $attribute)
            <tr>
                <td>{{ $attribute->name }}</td>
                <td>{{ implode(', ', $attribute->attributeValues->pluck('value_uk')->toArray()) }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endif
