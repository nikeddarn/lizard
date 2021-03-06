<div class="table-responsive">

    <table class="table">

        <thead>

        <tr>
            {{-- Created at--}}
            <td>
                <strong>Создан</strong>
            </td>

            {{-- Published--}}
            <td>
                <strong>Опубликован</strong>
            </td>

            <td class="d-none d-lg-table-cell text-center"><strong>Изображение</strong></td>

            {{-- Name--}}
            <td>
                <strong>Наименование</strong>
            </td>

            <td class="text-center">
                <strong>Прибыль ($)</strong>
            </td>

            <td class="text-center">
                <strong>Прибыль (%)</strong>
            </td>

            <td></td>

        </tr>

        </thead>

        <tbody>


        @foreach($products as $product)

            <tr class="text-center">

                <td>{{ $product->created_at->format('d - m - Y') }}</td>

                <td>
                    @if($product->published)
                        <i class="svg-icon-larger text-success" data-feather="eye"></i>
                    @else
                        <i class="svg-icon-larger text-danger" data-feather="eye"></i>
                    @endif
                </td>

                <td class="d-none d-lg-table-cell">
                    @if($product->primaryImage)
                        <img src="/storage/{{ $product->primaryImage->small }}" class="img-responsive table-image">
                    @endif
                </td>

                <td>
                    @if($product->published)
                        <a href="{{ url(route('shop.product.index', ['url' => $product->url])) }}">{{ $product->name }}</a>
                    @else
                        <span>{{ $product->name }}</span>
                    @endif
                </td>

                <td>
                    @if($product->profitSum)
                        {{ $product->profitSum }}
                    @endif
                </td>

                <td>
                    @if($product->profitPercents)
                        {{ $product->profitPercents }}
                    @endif
                </td>

                <td>

                    <div class="product-actions d-flex justify-content-center align-items-start">

                        <form
                            class="products-publish-off-form ml-1 {{ $product->dealerProduct && $product->dealerProduct->published ? 'd-inline-block' : 'd-none' }}"
                            action="{{ route('admin.export.hotline.product.publish.off') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                    title="Выключить публикацию продукта">
                                <i class="svg-icon-larger" data-feather="check-circle"></i>
                            </button>
                        </form>

                        <form
                            class="products-publish-on-form ml-1 {{ $product->dealerProduct && $product->dealerProduct->published ? 'd-none' : 'd-inline-block' }}"
                            action="{{ route('admin.export.hotline.product.publish.on') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                    title="Включить публикацию продукта">
                                <i class="svg-icon-larger" data-feather="check-circle"></i>
                            </button>
                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

</div>
