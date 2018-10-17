<div id="product-attributes-list"></div>

@if($attributes->count())

<div class="text-right mb-5">
    <button id="product-attribute-add-button" class="btn btn-primary" type="button">
        <i class="fa fa-plus"></i>&nbsp;
        <span>Добавить атрибут</span>
    </button>
</div>

@else

    <div class="mb-5">
        Нет ни одного атрибута. Создайте продукт затем создайте атрибуты и добавьте их к продукту.
    </div>

@endif