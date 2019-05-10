<div id="product-categories-list">

    <div class="product-category-item card p-5 mb-5">
        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Категория</label>
            </div>
            <div class="col-sm-8">
                <select name="categories_id[]" class="selectpicker w-100">
                    @include('content.admin.catalog.product.create.parts.indexed_select_category_options', ['categoryIndex' => 0])
                </select>
            </div>
        </div>
    </div>

    @if(old('categories_id'))
        @foreach(old('categories_id') as $categoryIndex => $categoryId)

            @if($categoryIndex === 0)
                @continue
            @endif

            <div class="product-category-item card p-5 mb-5">

                <button type="button" class="btn btn-danger product-category-item-delete mr-md-2 mt-md-2"
                        data-toggle="tooltip"
                        title="Удалить категорию">
                    <i class="fa fa-trash-o"></i>&nbsp;
                </button>

                <div class="row form-group">
                    <div class="col-sm-2">
                        <label class="required">Категория</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="categories_id[]" class="selectpicker w-100">
                            @include('content.admin.catalog.product.create.parts.indexed_select_category_options', ['categoryIndex' => $categoryIndex])
                        </select>
                    </div>
                </div>
            </div>

        @endforeach
    @endif

</div>


<div class="text-right mb-5">
    <button id="product-category-add-button" class="btn btn-primary" type="button">
        <i class="fa fa-plus"></i>&nbsp;
        <span>Добавить категорию</span>
    </button>
</div>