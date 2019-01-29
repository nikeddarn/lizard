<ul class="list-group list-group-flush">

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Родитель</strong>
            </div>
            <div class="col col-lg-8">
                @if($category->parent)
                    {{ $category->parent->name }}
                @else
                    Корневая категория
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Наименование (RU)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->name_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Наименование (UA)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->name_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>URL</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->url }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>SEO Title (RU)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->title_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>SEO Title (UA)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->title_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>SEO Description (RU)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->description_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>SEO Description (UA)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->description_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>SEO Keywords (RU)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->keywords_ru }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>SEO Keywords (UA)</strong>
            </div>
            <div class="col col-lg-8">
                {{ $category->keywords_uk }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col col-lg-4">
                <strong>Описание (RU)</strong>
            </div>
            <div class="col col-lg-8">
                @if($category->content_ru)
                    <div class="show-content-property">
                        {!! $category->content_ru !!}
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
                @if($category->content_uk)
                    <div class="show-content-property">
                        {!! $category->content_uk !!}
                    </div>
                @endif
            </div>
        </div>
    </li>

</ul>
