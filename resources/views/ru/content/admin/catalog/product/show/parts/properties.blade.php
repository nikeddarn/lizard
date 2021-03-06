<ul class="list-group-flush">

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Наименование (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->name_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Наименование (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->name_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Модель (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->model_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Модель (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->model_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>URL</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->url }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Страна производитель (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->manufacturer_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Страна производитель (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->manufacturer_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>SEO Title (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->title_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>SEO Title (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->title_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>SEO Description (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->description_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>SEO Description (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->description_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>SEO Keywords (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->keywords_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>SEO Keywords (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->keywords_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Короткое описание (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                @if($product->brief_content_ru)
                    <div class="show-content-property">
                        {!! $product->brief_content_ru !!}
                    </div>
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Короткое описание (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                @if($product->brief_content_uk)
                    <div class="show-content-property">
                        {!! $product->brief_content_uk !!}
                    </div>
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Описание (RU)</strong>
            </div>
            <div class="col-6 col-lg-8">
                @if($product->content_ru)
                    <div class="show-content-property">
                        {!! $product->content_ru !!}
                    </div>
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Описание (UA)</strong>
            </div>
            <div class="col-6 col-lg-8">
                @if($product->content_uk)
                    <div class="show-content-property">
                        {!! $product->content_uk !!}
                    </div>
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Цена 1</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->price1 }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Цена 2</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->price2 }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Цена 3</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->price3 }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Состояние</strong>
            </div>
            <div class="col-6 col-lg-8">
                @if($product->is_new)
                    <span>Новый</span>
                @else
                    <span>Б/У</span>
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Гарантия</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->warranty }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Вес (кг)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->weight }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Длина (см)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->length }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Ширина (см)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->width }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Высота (см)</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $product->height }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Объем (м<sup>3</sup>)</strong>
            </div>
            <div class="col-6 col-lg-8">
                @if($product->volume)
                    {{ $product->volume }}
                @endif
            </div>
        </div>
    </li>

</ul>
