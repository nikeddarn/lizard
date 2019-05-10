@if($mainSlider->slides->count())
    <div class="card p-5 mb-5">

        <div class="alert alert-info mb-5">Перетягивайте мышкой, чтобы изменить порядок</div>

        <form method="post" action="{{ route('admin.content.main.sort.slides') }}" role="form">
            @csrf

            <div id="sortable-slides" class="row">
                @foreach($mainSlider->slides as $image)
                    <div class="col-6 col-md-3">
                        <div class="slider-item">
                            <div class="slider-item-control">
                                <a href="{{ route('admin.slider.slide.edit', ['slide_id' => $image->id]) }}"
                                   class="btn btn-primary" title="Редактировать слайд">
                                    <i class="svg-icon-larger" data-feather="edit"></i>
                                </a>
                                <button class="btn btn-danger" form="delete-slide-form-{{ $image->id }}"
                                        type="submit"
                                        title="Удалить слайд">
                                    <i class="svg-icon-larger" data-feather="x-circle"></i>
                                </button>
                            </div>
                            <img src="{{ url('/storage/' . $image->image_ru) }}">
                            <input type="hidden" name="slide_id[]" value="{{ $image->id }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="sort-slide-button" class="text-right mt-5 d-none">
                <button type="submit" class="btn btn-primary">Изменить порядок сортировки</button>
            </div>

        </form>

        @foreach($mainSlider->slides as $image)
            {{--delete slide forms--}}
            <form id="delete-slide-form-{{ $image->id }}" class="delete-slide-form" method="post"
                  action="{{ route('admin.slider.slide.delete') }}"
                  role="form">
                @csrf
                <input type="hidden" name="slide_id" value="{{ $image->id }}">
            </form>
        @endforeach

    </div>
@endif

<div class="text-right">
    <a href="{{ route('admin.slider.slide.create', ['slider_id' => $mainSlider->id]) }}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Добавить слайд</span>
    </a>
</div>
