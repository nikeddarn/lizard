<div id="main-carousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($mainSlider->slides as $slide)
            <div class="carousel-item{{ $loop->first ? ' active' : '' }}">
                <a href="{{ $slide->url_uk }}" class="d-block h-100">
                    <img class="d-block w-100" src="{{ url('/storage/' . $slide->image_uk) }}"
                         alt="{{ $slide->name_uk }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h1>{{ $slide->name_uk }}</h1>
                        <p class="h4">{{ $slide->text_uk }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#main-carousel" role="button" data-slide="prev">
        <i class="carousel-control-icon" data-feather="chevron-left"></i>
        <span class="sr-only">Попередній</span>
    </a>

    <a class="carousel-control-next" href="#main-carousel" role="button" data-slide="next">
        <i class="carousel-control-icon" data-feather="chevron-right"></i>
        <span class="sr-only">Наступний</span>
    </a>
</div>
