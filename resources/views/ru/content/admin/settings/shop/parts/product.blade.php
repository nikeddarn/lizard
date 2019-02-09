<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="show-items-per-page">Количество продуктов на странице</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="show-items-per-page" class="form-control default-bootstrap-select-input" type="number"
                       name="show_products_per_page"
                       value="{{ $productData['show_products_per_page'] }}" min="1" max="100">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="show-product-comments-per-page">Количество комментариев на странице продукта</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="show-product-comments-per-page" class="form-control default-bootstrap-select-input"
                       type="number"
                       name="show_product_comments_per_page"
                       value="{{ $productData['show_product_comments_per_page'] }}" min="1" max="100">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="recent-product-ttl">Показывать недавно просмотренные продукты (дней)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="recent-product-ttl" class="form-control default-bootstrap-select-input" type="number"
                       name="recent_product_ttl" value="{{ $productData['recent_product_ttl'] }}" min="1" max="100">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="allow-show-product-rate" name="allow_show_product_rate"
               class="custom-control-input multi-inputs-checkbox"{{ $productData['show_rate']['allowed'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="allow-show-product-rate">Показывать рейтинг продукта</label>
    </div>

    <div class="row">

        <div
            class="col col-sm-6 col-md-5 col-xl-3 multi-inputs-related-field{{ $productData['show_rate']['allowed'] ? ' visible' : ' invisible' }}">
            <label for="show-rate-review-count">Показывать начиная с количества отзывов</label>
            <input id="show-rate-review-count" class="default-bootstrap-select-input"
                   name="show_product_rate_from_review_counts"
                   value="{{ $productData['show_rate']['count'] }}" type=number min="1" max="100">
        </div>

    </div>

</div>

<div class="card p-5 mb-5">

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="allow-show-product-defect-rate" name="allow_show_product_defect_rate"
               class="custom-control-input multi-inputs-checkbox"{{ $productData['show_defect_rate']['allowed'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="allow-show-product-defect-rate">Показывать процент брака
            продукта</label>
    </div>

    <div class="row">

        <div
            class="col col-sm-6 col-md-5 col-xl-3 multi-inputs-related-field{{ $productData['show_defect_rate']['allowed'] ? ' visible' : ' invisible' }}">
            <label for="show-defect-rate-sold-count">Показывать начиная с количества купленных продуктов</label>
            <input id="show-defect-rate-sold-count" class="default-bootstrap-select-input"
                   name="show_product_defect_rate_from_sold_counts"
                   value="{{ $productData['show_defect_rate']['count'] }}" type=number min="1" max="100">
        </div>

    </div>

</div>
