@if($comments->count())

    @foreach($comments as $comment)

        <div class="product-comment media mb-5">

            <div class="media-left mr-4">

            @if($comment->user && $comment->user->avatar)
                <img src="/storage/{{ $comment->user->avatar }}" class="media-object img-thumbnail">
            @else
                <img src="/images/common/default_user_avatar.png" class="media-object img-thumbnail">
            @endif

            @if($comment->rating)
                <div class="mt-2">
                    <div class="product-rating">
                        @for($i=1; $i<=5; $i++)
                            @if($product->rating >= $i)
                                <span class="fa fa-star" aria-hidden="true"></span>
                            @else
                                <span class="fa fa-star-o" aria-hidden="true"></span>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif

            </div>

            <div class="media-body">

                <h5 class="media-heading text-gray">
                    @if($comment->user)
                        <strong>{{ $comment->user->name }}</strong>
                    @else
                        <strong>{{ $comment->name }}</strong>
                    @endif
                    <small class="ml-4">{{ $comment->updated_at->diffForHumans() }}</small>
                </h5>

                <div>{{ $comment->comment }}</div>
            </div>
        </div>

    @endforeach

    @if($comments->links())
        <div class="my-4">{{ $comments->links() }}</div>
    @endif

    <hr>

@endif

@if($comments->count())
    @include('content.shop.product.details.parts.dropdown_comment_form')
@else
    @include('content.shop.product.details.parts.comment_form')
@endif

