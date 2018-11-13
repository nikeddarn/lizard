<div class="card py-2 px-1 p-lg-5 mb-5">

    <h4 class="mb-5 text-center">Свойства категории</h4>

    <ul class="list-group">

        <li class="list-group-item">
            <div class="row">
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
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Изображение</strong>
                </div>
                <div class="col col-lg-8">
                    <img class="table-image img-fluid" src="/storage/{{ $category->image }}">
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Наименование (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->name_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Наименование (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->name_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>URL</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->url }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Title (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->title_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Title (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->title_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Description (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->description_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Description (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->description_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Keywords (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->keywords_ru }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>SEO Keywords (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $category->keywords_ua }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Описание (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    @if($category->content_ru)
                        <a data-toggle="collapse" href="#content_ru" role="button" aria-expanded="false"
                           aria-controls="content_ru">
                            <i class="fa fa-eye"></i>&nbsp;
                            <span>Смотреть описание</span>
                        </a>
                        <div class="collapse" id="content_ru">
                            <div class="card card-body">{!! $category->content_ru !!}</div>
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
                    @if($category->content_ua)
                        <a data-toggle="collapse" href="#content_ua" role="button" aria-expanded="false"
                           aria-controls="content_ua">
                            <i class="fa fa-eye"></i>&nbsp;
                            <span>Смотреть описание</span>
                        </a>
                        <div class="collapse" id="content_ua">
                            <div class="card card-body">{!! $category->content_ua !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </li>

    </ul>

</div>