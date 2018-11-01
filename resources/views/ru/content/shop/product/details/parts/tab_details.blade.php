@if($product->attributeValues->count())
    <table id="product-attributes-table" class="table table-bordered">
        <tbody>

        @foreach($product->attributeValues as $attributeValue)
            <tr>
                <td>{{ $attributeValue->attribute->name }}</td>
                <td>{{ $attributeValue->value }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endif