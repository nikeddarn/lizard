@if($product->productVideos->count())
    <div class="row">
        @foreach($product->productVideos as $video)

            <div class="col-6 col-md-3">
                    <div class="slider-item">
                        <div class="slider-item-control">
                            <div class="d-flex justify-content-end align-items-start">

                                <form class="product-video-delete-form"
                                      action="{{ route('admin.products.video.destroy', ['id' => $video->id]) }}"
                                      method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-1" data-toggle="tooltip"
                                            title="Удалить видео продукта">
                                        <i class="svg-icon-larger" data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($video->youtube)
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe src="{{ $video->youtube }}" frameborder="0"
                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="embed-responsive embed-responsive-16by9">
                                <video class="embed-responsive-item" controls>
                                    @if($video->mp4)
                                        <source src="/storage/{{ $video->mp4 }}" type="video/mp4">
                                    @endif
                                    @if($video->webm)
                                        <source src="/storage/{{ $video->webm }}" type="video/webm">
                                    @endif
                                </video>
                            </div>
                        @endif

                    </div>
            </div>

        @endforeach
    </div>

@endif

<div class="my-5 text-right">
    <a href="{{route('admin.products.video.create', ['id' => $product->id])}}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Добавить видео продукта</span>
    </a>
</div>
