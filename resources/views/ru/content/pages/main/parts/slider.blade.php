<div id="main-carousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($mainSlider->slides as $slide)
            <div class="carousel-item{{ $loop->first ? ' active' : '' }}"
                 style="background-image: url({{ url('/storage/' . $slide->image_ru) }})">
                <a href="{{ $slide->url_ru }}" class="d-block h-100">
                    <div class="carousel-caption d-none d-md-block">
                        <h1>{{ $slide->name_ru }}</h1>
                        <p class="h4">{{ $slide->text_ru }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#main-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Предыдущий</span>
    </a>
    <a class="carousel-control-next" href="#main-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Следующий</span>
    </a>
</div>
