<div class="table-responsive" style="overflow: visible">

    <table class="table">

        <thead>

        <tr class="text-center">
            {{-- Created at--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->has('createdAt'))
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="createdAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Создан</span>
                                @if(request()->get('createdAt') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="createdAtDropdown">
                                @if(request()->get('createdAt') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['createdAt' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->get('createdAt') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['createdAt' => 'asc']), ['page' => ''])) }}">По
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
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['createdAt' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['createdAt' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            <td><strong>Id заказа</strong></td>

            <td><strong>Сумма</strong></td>

            {{-- Delivery--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->has('deliveryType'))
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="deliveryTypeDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @foreach($deliveryTypes as $deliveryType)
                                    @if(request()->get('deliveryType') == $deliveryType->id)
                                        <span>{{ $deliveryType->name_ru }}</span>
                                    @endif
                                @endforeach
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="deliveryTypeDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query()), ['page' => '', 'deliveryType' => ''])) }}">Все типы доставки</a>
                                @foreach($deliveryTypes as $deliveryType)
                                    @if(request()->get('deliveryType') == $deliveryType->id)
                                        <span
                                            class="dropdown-item cursor-pointer disabled">{{ $deliveryType->name_ru }}</span>
                                    @else
                                        <a class="dropdown-item"
                                           href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['deliveryType' => $deliveryType->id]), ['page' => ''])) }}">{{ $deliveryType->name_ru }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="deliveryTypeDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Доставка</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="deliveryTypeDropdown">
                                @foreach($deliveryTypes as $deliveryType)
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['deliveryType' => $deliveryType->id]), ['page' => ''])) }}">{{ $deliveryType->name_ru }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Status--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->has('orderStatus'))
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="orderStatusDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @foreach($orderStatusTypes as $orderStatus)
                                    @if(request()->get('orderStatus') == $orderStatus->id)
                                        <span>{{ $orderStatus->name_ru }}</span>
                                    @endif
                                @endforeach
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="orderStatusDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query()), ['page' => '', 'orderStatus' => ''])) }}">Все статусы заказа</a>
                                @foreach($orderStatusTypes as $orderStatus)
                                    @if(request()->get('orderStatus') == $orderStatus->id)
                                        <span
                                            class="dropdown-item cursor-pointer disabled">{{ $orderStatus->name_ru }}</span>
                                    @else
                                        <a class="dropdown-item"
                                           href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['orderStatus' => $orderStatus->id]), ['page' => ''])) }}">{{ $orderStatus->name_ru }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="orderStatusDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Статус</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="orderStatusDropdown">
                                @foreach($orderStatusTypes as $orderStatus)
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['orderStatus' => $orderStatus->id]), ['page' => ''])) }}">{{ $orderStatus->name_ru }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            <td></td>

        </tr>

        </thead>

        <tbody>


        @foreach($orders as $order)

            <tr class="text-center">

                <td>{{ $order->created_at->formatLocalized('%e %B %Y') }}</td>

                <td>{{ $order->id }}</td>

                <td>{{ number_format($order->total_sum) }} грн</td>

                <td>{{ $order->deliveryType->name_ru }}</td>

                <td>
                    <span
                        class="badge-bigger badge badge-{{ $order->orderStatus->class }}">{{ $order->orderStatus->name_ru }}</span>
                </td>

                <td>

                    <div class="product-actions d-flex justify-content-center align-items-start">

                        {{--                        <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"--}}
                        {{--                           title="Просмотреть" class="btn btn-primary">--}}
                        {{--                            <i class="svg-icon-larger" data-feather="eye"></i>--}}
                        {{--                        </a>--}}

                        {{--                        <form--}}
                        {{--                            class="products-publish-off-form ml-1 {{ $product->published ? 'd-inline-block' : 'd-none' }}"--}}
                        {{--                            action="{{ route('admin.products.publish.off') }}" method="post">--}}
                        {{--                            @csrf--}}
                        {{--                            <input type="hidden" name="product_id" value="{{ $product->id }}">--}}
                        {{--                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"--}}
                        {{--                                    title="Выключить публикацию продукта">--}}
                        {{--                                <i class="svg-icon-larger" data-feather="check-circle"></i>--}}
                        {{--                            </button>--}}
                        {{--                        </form>--}}

                        {{--                        <form--}}
                        {{--                            class="products-publish-on-form ml-1 {{ $product->published ? 'd-none' : 'd-inline-block' }}"--}}
                        {{--                            action="{{ route('admin.products.publish.on') }}" method="post">--}}
                        {{--                            @csrf--}}
                        {{--                            <input type="hidden" name="product_id" value="{{ $product->id }}">--}}
                        {{--                            <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"--}}
                        {{--                                    title="Включить публикацию продукта">--}}
                        {{--                                <i class="svg-icon-larger" data-feather="check-circle"></i>--}}
                        {{--                            </button>--}}
                        {{--                        </form>--}}

                        {{--                        <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"--}}
                        {{--                           title="Редактировать" class="btn btn-primary mx-1">--}}
                        {{--                            <i class="svg-icon-larger" data-feather="edit"></i>--}}
                        {{--                        </a>--}}

                        {{--                        <form class="product-delete-form d-inline-block"--}}
                        {{--                              action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">--}}
                        {{--                            @csrf--}}
                        {{--                            @method('DELETE')--}}

                        {{--                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">--}}
                        {{--                                <i class="svg-icon-larger" data-feather="trash-2"></i>--}}
                        {{--                            </button>--}}
                        {{--                        </form>--}}

                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

</div>
