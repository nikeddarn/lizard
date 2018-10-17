@foreach($children as $child)

    <div class="row mb-4">

        <div class="col-lg-12 my-4">
            <h2>{{ $child->filter }}</h2>
        </div>

        @foreach(json_decode($child->subcategories) as $subcategory)

            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 my-2">
                <div class="card">
                    <a class="btn" href="{{ route('shop.category.index', ['url' => $subcategory->url]) }}">
                        <img class="card-img-top" src="/storage/{{ $subcategory->image }}">
                        <div class="card-body">
                            <h4 class="card-title text-gray">{{ $subcategory->name }}</h4>
                        </div>
                    </a>
                </div>
            </div>

        @endforeach

    </div>

    <hr>

@endforeach