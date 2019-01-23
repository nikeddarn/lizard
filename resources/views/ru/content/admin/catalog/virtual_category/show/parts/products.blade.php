@if($products->count())

    <div class="table-responsive">
        <table class="table">

            <thead>
            <tr class="text-center">
                <td><strong>Изображение</strong></td>
                <td><strong>Id</strong></td>
                <td><strong>Название</strong></td>
                <td><strong>Поставщики</strong></td>
                <td></td>
            </tr>
            </thead>

            <tbody>

            @foreach($products as $product)

                <tr class="text-center">

                    <td>
                        @if($product->primaryImage)
                            <img src="/storage/{{ $product->primaryImage->small }}" class="img-fluid table-image"
                                 alt="Изображение {{ $product->name }}">
                        @endif
                    </td>

                    <td>{{ $product->id }}</td>

                    <td>{{ $product->name }}</td>

                    <td>
                        @if($product->vendors->count())
                            {{ implode(',', $product->vendors->pluck('name_ru')->toArray()) }}
                        @else
                            Собственный продукт
                        @endif
                    </td>


                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"
                               title="Просмотреть" class="btn btn-primary">
                                <i class="svg-icon-larger" data-feather="eye"></i>
                            </a>

                            <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
                               title="Редактировать" class="btn btn-primary mx-1">
                                <i class="svg-icon-larger" data-feather="edit"></i>
                            </a>

                            <form class="d-inline-block product-delete-form"
                                  action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                        title="Удалить продукт">
                                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>
    </div>
@endif

@if($products->lastPage() !== 1)
    <div class="my-4 mx-negative-3">
        @include('layouts.parts.pagination.products.index', ['paginator' => $products])
    </div>
@endif

<div class="my-2 text-right">
    <a href="{{route('admin.products.create')}}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span class="ml-2">Создать продукт</span>
    </a>
</div>
