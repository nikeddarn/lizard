<div id="product-category-input-template">

    <div class="product-category-item card p-5 mb-5 d-none">

        <button type="button" class="btn btn-danger product-category-item-delete mr-md-2 mt-md-2" data-toggle="tooltip"
                title="Удалить категорию">
            <i class="fa fa-trash-o"></i>&nbsp;
        </button>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Категория</label>
            </div>
            <div class="col-sm-8">
                <select name="categories_id[]" class="category-id-select w-100">
                    @include('content.admin.catalog.product.create.parts.select_category_options')
                </select>
            </div>
        </div>

    </div>

</div>