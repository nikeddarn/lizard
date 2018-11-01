<div class="mb-2">
    @if($product->productImages->count())
        <img id="zoom-image" src="/storage/{{ $product->productImages->first()->large }}"
             data-zoom-image="/storage/{{ $product->productImages->first()->large }}">
    @else
        <img src="/images/common/no_image.png">
    @endif
</div>

@if($product->productImages->count() > 1)

    <div class="owl-carousel owl-theme">

        @foreach($product->productImages as $image)

            <div class="item">
                <img src="/storage/{{ $image->large }}" class="img-thumbnail">
            </div>

        @endforeach

    </div>

@endif