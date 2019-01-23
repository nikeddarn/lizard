<ul class="nav nav-tabs my-5" role="tablist">
    <li class="nav-item">
        <a class="nav-link active text-secondary" data-toggle="tab" href="#product-content" role="tab"
           aria-controls="product-content"
           aria-selected="true">Описание</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-secondary" data-toggle="tab" href="#product-properties" role="tab"
           aria-controls="product-properties"
           aria-selected="false">Детали</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-secondary" data-toggle="tab" href="#product-reviews" role="tab"
           aria-controls="product-reviews"
           aria-selected="false">Отзывы</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="product-content" role="tabpanel" aria-labelledby="product-content-tab">
        <div class="text-gray-hover">
            {!! $product->content !!}
        </div>
    </div>
    <div class="tab-pane fade" id="product-properties" role="tabpanel" aria-labelledby="product-properties-tab">
        @include('content.shop.product.details.parts.tab_details')
    </div>
    <div class="tab-pane fade" id="product-reviews" role="tabpanel" aria-labelledby="product-reviews-tab">
        @include('content.shop.product.details.parts.tab_reviews')
    </div>
</div>
