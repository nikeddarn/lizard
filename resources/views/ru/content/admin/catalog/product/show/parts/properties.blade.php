<div class="card py-2 px-1 p-lg-5 mb-5">

    <h4 class="mb-5 text-center">Свойства продукта</h4>

    <ul class="list-group">

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Наименование (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->name_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Наименование (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->name_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>URL</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->url }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Бренд</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->brand ? $product->brand->name : '' }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Страна производитель (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->manufacturer_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Страна производитель (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->manufacturer_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Title (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->title_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Title (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->title_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Description (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->description_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Description (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->description_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Keywords (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->keywords_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Keywords (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->keywords_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Короткое описание (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($product->brief_content_ru)
                        <a data-toggle="collapse" href="#brief_content_ru" role="button" aria-expanded="false"
                           aria-controls="brief_content_ru">
                            <i class="fa fa-eye"></i>&nbsp;
                            <span>Смотреть короткое описание</span>
                        </a>
                        <div class="collapse" id="brief_content_ru">
                            <div class="card card-body">{!! $product->brief_content_ru !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Короткое описание (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($product->brief_content_ua)
                        <a data-toggle="collapse" href="#brief_content_ua" role="button" aria-expanded="false"
                           aria-controls="brief_content_ua">
                            <i class="fa fa-eye"></i>&nbsp;
                            <span>Смотреть короткое описание</span>
                        </a>
                        <div class="collapse" id="brief_content_ua">
                            <div class="card card-body">{!! $product->brief_content_ua !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Описание (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($product->content_ru)
                        <a data-toggle="collapse" href="#content_ru" role="button" aria-expanded="false"
                           aria-controls="content_ru">
                            <i class="fa fa-eye"></i>&nbsp;
                            <span>Смотреть описание</span>
                        </a>
                        <div class="collapse" id="content_ru">
                            <div class="card card-body">{!! $product->content_ru !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Описание (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($product->content_ua)
                        <a data-toggle="collapse" href="#content_ua" role="button" aria-expanded="false"
                           aria-controls="content_ua">
                            <i class="fa fa-eye"></i>&nbsp;
                            <span>Смотреть описание</span>
                        </a>
                        <div class="collapse" id="content_ua">
                            <div class="card card-body">{!! $product->content_ua !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Цена 1</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->price1 }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Цена 2</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->price2 }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Цена 3</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->price3 }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Состояние</strong>
                </div>
                <div class="col col-lg-8">
                    @if($product->is_new)
                        <span>Новый</span>
                    @else
                        <span>Б/У</span>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Гарантия</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->warranty }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Длина (см)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->length }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Ширина (см)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->width }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Высота (см)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $product->height }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Объем (м<sup>3</sup>)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($product->volume)
                        {{ number_format($product->volume, 8) }}
                    @endif
                </div>
            </div>
        </li>

    </ul>

</div>