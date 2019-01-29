<ul class="list-group list-group-flush">

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Наименование (RU)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $attribute->name_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Наименование (UA)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $attribute->name_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Несколько значений у одного продукта</strong>
            </div>
            <div class="col col-lg-8">
                @if($attribute->multiply_product_values)
                    Разрешено
                @else
                    Запрещено
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Индексировать категорию при применении фильтра с значением этого
                    атрибута</strong>
            </div>
            <div class="col col-lg-8">
                @if($attribute->indexable)
                    Разрешено
                @else
                    Запрещено
                @endif
            </div>
        </div>
    </li>

</ul>
