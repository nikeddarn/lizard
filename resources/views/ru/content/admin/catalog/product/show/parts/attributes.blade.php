@if($product->productAttributes->count())

    <div class="table-responsive">

        <table class="table">

            <tbody>

            @foreach($product->productAttributes as $productAttribute)

                <tr>

                    <td>
                        <strong>{{ $productAttribute->attributeValue->attribute->name_ru }}</strong>
                    </td>

                    <td>{{ $productAttribute->attributeValue->value_ru }}</td>


                    <td class="text-right">

                        <form class="d-inline-block product-attribute-delete-form"
                              action="{{ route('admin.products.attribute.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="products_id" value="{{ $product->id }}">
                            <input type="hidden" name="attribute_values_id"
                                   value="{{ $productAttribute->attribute_values_id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить атрибут продукта">
                                <i class="svg-icon-larger" data-feather="trash-2"></i>
                            </button>
                        </form>

                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>

    </div>

@endif

<div class="col-lg-12 my-4 text-right">
    <a href="{{route('admin.products.attribute.create', ['id' => $product->id])}}" class="btn btn-primary">
        <i class="fa fa-plus"></i>&nbsp;
        <span>Добавить атрибут продукта</span>
    </a>
</div>
