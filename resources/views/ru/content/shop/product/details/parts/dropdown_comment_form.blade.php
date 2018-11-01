<a class="mb-4 d-block" data-toggle="collapse" href="#formReview" role="button" aria-expanded="false" aria-controls="formReview">
    <h4>Добавьте ваш отзыв</h4>
</a>

<div class="collapse" id="formReview">
    <form id="formReview" action="{{ route('product.comments.store') }}" method="post" role="form">

        @csrf

        <input type="hidden" name="products_id" value="{{ $product->id }}">

        @if(!auth('web')->check())
            <div class="form-group">
                <label for="product-comment-name">Имя</label>
                <input id="product-comment-name" type="text" class="form-control" placeholder="Имя" name="name"
                       maxlength="32">
                @if ($errors->has('name'))
                    <span class="help-block alert alert-danger">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        @endif

        <div class="form-group">
            <label>Рейтинг продукта</label>
            <div class="clearfix"></div>
            <div class="product-rating">
                <input type="hidden" class="rating" name="rating" data-filled="fa fa-star product-rating mx-1"
                       data-empty="fa fa-star-o product-rating mx-1" value="0">
            </div>
        </div>

        <div class="form-group">
            <label for="comment">Ваш комментарий</label>
            <textarea id="comment" class="form-control" rows="5" placeholder="Напишите отзыв о продукте"
                      name="comment"
                      maxlength="512"></textarea>
            @if ($errors->has('comment'))
                <span class="help-block alert alert-danger">
                <strong>{{ $errors->first('comment') }}</strong>
            </span>
            @endif
        </div>


        <button type="submit" class="btn btn-primary">Отправить отзыв</button>

    </form>
</div>