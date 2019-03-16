@if($product->categories->count())

    <div class="table-responsive">

        <table class="table">

            <thead>
            <tr>
                <td><strong>Локальная категория</strong></td>
                <td class="text-center"></td>
            </tr>
            </thead>

            <tbody>

            @foreach($product->categories as $category)

                <tr>

                    <td>{{ $category->name }}</td>

                    <td class="text-right">

                        <form class="d-inline-block product-category-delete-form"
                              action="{{ route('admin.products.category.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="products_id" value="{{ $product->id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить продукт из категории">
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
    <a href="{{route('admin.products.category.create', ['id' => $product->id])}}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Добавить товар в категорию</span>
    </a>
</div>
