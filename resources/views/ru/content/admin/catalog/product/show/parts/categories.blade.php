<div class="card py-2 px-1 p-lg-5 mb-5">
    <h4 class="mb-5 text-center">Размещение в категориях</h4>

    @if($product->categories->count())

        <table class="table">

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
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>

                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>

    @endif

    <div class="col-lg-12 my-4 text-right">
        <a href="{{route('admin.products.category.create', ['id' => $product->id])}}" class="btn btn-primary">
            <i class="fa fa-plus"></i>&nbsp;
            <span>Добавить товар в категорию</span>
        </a>
    </div>

</div>
