<div class="container-fluid bg-white">

    <h2 class="h5 text-gray-hover px-4 pt-4">Продукты {{ $brandAttributeValue->value_ru }} в других категориях</h2>

    <div id="relatedCategoriesCarousel" class="owl-carousel owl-theme">

        @foreach($linkedCategories as $category)
            <div class="owl-item">
                <a href="{{ $category->href }}">
                    <div class="card h-100">
                        @if($category->image)
                            <div class="card-body">
                                <img class="card-image w-100" src="/storage/{{ $category->image }}">
                            </div>
                        @endif

                        <div class="card-footer bg-white">
                            <h2 class="h6 text-gray-hover text-center">{{ $category->brandFilteredName }}</h2>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

    </div>

</div>
