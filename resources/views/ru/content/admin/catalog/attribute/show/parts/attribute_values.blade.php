<table class="table">

    <tbody>

    @foreach($attributeValues as $value)

        <tr>

            <td>
                <div class="d-flex">
                    @if($value->image)
                        <img src="/storage/{{ $value->image }}" class="table-image">
                    @endif
                    <span>{{ $value->value_ru }}</span>
                </div>
            </td>

            <td class="text-right">
                <a href="{{ route('admin.attributes.value.edit', ['id' => $value->id]) }}" data-toggle="tooltip"
                   title="Редактировать значение атрибута" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                <form class="d-inline-block delete-item-form ml-0 ml-md-2"
                      action="{{ route('admin.attribute.values.destroy', ['id' => $value->id]) }}"
                      method="post">
                    @csrf
                    <input type="hidden" name="_method" value="delete"/>
                    <input type="hidden" name="id" value="{{ $attribute->id }}">
                    <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                            title="Удалить значение атрибута">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </form>
            </td>

        </tr>


    @endforeach

    </tbody>

</table>

@if($attributeValues->lastPage() !== 1)
    <div class="my-4 mx-negative-3">
        @include('layouts.parts.pagination.products.index', ['paginator' => $attributeValues])
    </div>
@endif

<div class="col-lg-12 my-4 text-right">
    <a href="{{route('admin.attributes.value.create', ['attributeId' => $attribute->id])}}" class="btn btn-primary">
        <i class="fa fa-plus"></i>&nbsp;
        <span>Добавить вариант значения</span>
    </a>
</div>
