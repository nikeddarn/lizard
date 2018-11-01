@foreach($groupedChildren as $filter => $group)

    <div class="row mb-4">

        <div class="col-lg-12 my-4">
            <h2>{{ $filter }}</h2>
        </div>

        @foreach($group as $subcategory)

            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 my-2">
                <div class="card">
                    <a class="btn" href="{{ $subcategory->isLeaf() ? route('shop.category.leaf.index', ['url' => $subcategory->url]) : route('shop.category.index', ['url' => $subcategory->url])}}">
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