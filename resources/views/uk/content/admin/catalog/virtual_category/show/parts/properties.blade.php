<div class="card card-body">

    <ul class="list-group list-group-flush">

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>Реальная категория</strong>
                </div>
                <div class="col col-lg-8">
                    <a href="{{ route('shop.category.leaf.index', ['url' => $virtualCategory->category->url]) }}">{{ $virtualCategory->category->name_ru }}</a>
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>Значение фильтра</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->attributeValue->value_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>URL</strong>
                </div>
                <div class="col col-lg-8">
                    <a href="{{ route('shop.category.filter.single', ['url' => $virtualCategory->category->url, 'filter' => $virtualCategory->attributeValue->url]) }}">{{ $virtualCategory->name_ru }}</a>
                </div>
            </div>
        </li>


        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>Наименование (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->name_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>Наименование (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->name_uk }}
                </div>
            </div>
        </li>



        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>SEO Title (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->title_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>SEO Title (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->title_uk }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>SEO Description (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->description_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>SEO Description (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->description_uk }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>SEO Keywords (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->keywords_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>SEO Keywords (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $virtualCategory->keywords_uk }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>Описание (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($virtualCategory->content_ru)
                        <div class="show-content-property">
                            {!! $virtualCategory->content_ru !!}
                        </div>
                    @endif
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row w-100">
                <div class="col col-lg-4">
                    <strong>Описание (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($virtualCategory->content_uk)
                        <div class="show-content-property">
                            {!! $virtualCategory->content_uk !!}
                        </div>
                    @endif
                </div>
            </div>
        </li>

    </ul>

</div>
