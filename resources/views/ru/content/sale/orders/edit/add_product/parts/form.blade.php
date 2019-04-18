<div class="row">

    <div class="col-12 col-sm-6">

        <div class="form-group">
            <label for="category_id" class="bold">Категория</label>
            <select id="category_id" name="category_id" class="selectpicker w-100" data-order-id="{{ $order->id }}">
                <option id="placeholder-category-option" disabled selected>Выберите категорию</option>
                @include('content.sale.orders.edit.add_product.parts.select_category_options')
            </select>
        </div>

    </div>

</div>
