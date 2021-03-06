<form id="product-category-form" method="post" action="{{ route('admin.products.category.store') }}" role="form">
    @csrf
    <input type="hidden" name="products_id" value="{{ $product->id }}">

    <div class="product-category-item card p-5 mb-5">
        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="product-category-select">Категория</label>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <select id="product-category-select" name="categories_id" class="selectpicker w-100">
                        @include('content.admin.catalog.product_category.create.parts.select_category_options')
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Сохранить категорию</button>
    </div>

</form>
