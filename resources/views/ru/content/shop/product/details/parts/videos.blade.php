<div class="row">
    @foreach($product->productVideos as $video)

        <div class="col-12 col-lg-6">
            @if($video->youtube)
                <iframe width="560" height="315" src="{{ $video->youtube }}" frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
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

    @endforeach
</div>
