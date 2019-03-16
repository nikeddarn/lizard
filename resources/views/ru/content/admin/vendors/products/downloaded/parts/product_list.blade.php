<div class="table-responsive">

    <table class="table">

        <thead>
        <tr class="text-center">

            {{-- Created at--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(!request()->query('sortBy') || request()->query('sortBy') === 'createdAt')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="createdAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Создан</span>
                                @if(!request()->query('sortMethod') || request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="createdAtDropdown">
                                @if(!request()->query('sortMethod') || request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="createdAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Создан</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="createdAtDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Downloaded at--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'downloadedAt')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="downloadedAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Загружен</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="downloadedAtDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'downloadedAt', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'downloadedAt', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="downloadedAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Загружен</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="downloadedAtDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'downloadedAt', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'downloadedAt', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Published--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'published')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="publishedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Публичный</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="publishedDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">Опубликованные в начале</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'desc']), ['page' => ''])) }}">Опубликованные
                                        в конце</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'asc']), ['page' => ''])) }}">Опубликованные
                                        в начале</a>
                                    <span class="dropdown-item disabled cursor-pointer">Опубликованные в конце</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="publishedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Публичный</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="publishedDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'asc']), ['page' => ''])) }}">Опубликованные
                                    в начале</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'desc']), ['page' => ''])) }}">Опубликованные
                                    в конце</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Archived--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'archived')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="archivedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Архивный</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="archivedDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item disabled cursor-pointer">Архивные в начале</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'desc']), ['page' => ''])) }}">Архивные
                                        в конце</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'asc']), ['page' => ''])) }}">Архивные
                                        в начале</a>
                                    <span class="dropdown-item disabled cursor-pointer">Архивные в конце</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="archivedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Архивный</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="archivedDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'asc']), ['page' => ''])) }}">Архивные
                                    в начале</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'desc']), ['page' => ''])) }}">Архивные
                                    в конце</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            <td class="d-none d-xl-table-cell"><strong>Изображение</strong></td>

            {{-- Name--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'name')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="nameDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Наименование</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="nameDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="nameDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Наименование</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="nameDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Country--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'country')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="countryDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Страна</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="countryDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'country', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'country', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="countryDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Страна</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="countryDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'country', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'country', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Warranty--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'warranty')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="warrantyDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Гарантия</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="warrantyDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'warranty', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'warranty', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="warrantyDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Гарантия</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="warrantyDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'warranty', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'warranty', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Price--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'price')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="priceDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Цена</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="priceDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'price', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'price', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="priceDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Цена</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="priceDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'price', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'price', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Profit sum--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'profitSum')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="profitSumDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Прибыль ($)</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="profitSumDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitSum', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitSum', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="profitSumDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Прибыль ($)</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="profitSumDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitSum', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitSum', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Profit percents--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'profitPercents')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="profitPercentsDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Прибыль (%)</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="profitPercentsDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitPercents', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitPercents', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="profitPercentsDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Прибыль (%)</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="profitPercentsDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitPercents', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'profitPercents', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            <td><strong>Действия</strong></td>

        </tr>
        </thead>

        <tbody>


        @foreach($downloadedVendorProducts as $vendorProduct)

            <tr class="text-center">

                <td>
                    @if($vendorProduct->vendor_created_at)
                        {{ $vendorProduct->vendor_created_at->format('d-m-Y') }}
                    @endif
                </td>

                <td>
                    @if($vendorProduct->created_at->format('d-m-Y'))
                        {{ $vendorProduct->created_at->format('d-m-Y') }}
                    @endif
                </td>

                <td class="product-published">
                    @if($vendorProduct->product->published)
                        <i class="svg-icon-larger text-success" data-feather="eye"></i>
                    @else
                        <i class="svg-icon-larger text-danger" data-feather="eye"></i>
                    @endif
                </td>

                <td>
                    @if($vendorProduct->is_archive)
                        <i class="svg-icon-larger" data-feather="archive"></i>
                    @endif
                </td>

                <td class="d-none d-xl-table-cell">
                    @if($vendorProduct->product->primaryImage)
                        <img src="{{ url('/storage/' . $vendorProduct->product->primaryImage->small) }}"
                             class="img-responsive table-image-smaller">
                    @endif
                </td>

                <td>
                    <a href="{{ route('admin.products.show', ['id' => $vendorProduct->product->id]) }}">{{ $vendorProduct->product->name }}</a>
                </td>

                <td>{{ $vendorProduct->product->manufacturer }}</td>

                <td>{{ $vendorProduct->warranty }}</td>

                <td>{{ $vendorProduct->price }}</td>

                <td>{{ $vendorProduct->profit }}</td>

                <td>{{ $vendorProduct->profitPercents }}</td>

                <td>

                    <div class="product-actions d-flex justify-content-center align-items-start">

                        <form
                            class="products-publish-off-form ml-1 {{ $vendorProduct->product->published ? 'd-inline-block' : 'd-none' }}"
                            action="{{ route('admin.products.publish.off') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $vendorProduct->product->id }}">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                    title="Выключить публикацию продукта">
                                <i class="svg-icon-larger" data-feather="check-circle"></i>
                            </button>
                        </form>

                        <form
                            class="products-publish-on-form ml-1 {{ $vendorProduct->product->published ? 'd-none' : 'd-inline-block' }}"
                            action="{{ route('admin.products.publish.on') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $vendorProduct->product->id }}">
                            <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                    title="Включить публикацию продукта">
                                <i class="svg-icon-larger" data-feather="check-circle"></i>
                            </button>
                        </form>

                        <form class="product-delete-form d-inline-block ml-1"
                              action="{{ route('vendor.category.products.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="vendor_product_id" value="{{ $vendorProduct->id }}">
                            <input type="hidden" name="product_id" value="{{ $vendorProduct->product->id }}">
                            <input type="hidden" name="categories_id" value="{{ $localCategory->id }}">
                            <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">

                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                                <i class="svg-icon-larger" data-feather="link-2"></i>
                            </button>
                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

</div>
