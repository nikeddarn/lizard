<div class="row">

    @foreach($children as $child)

        <div class="col-6 col-sm-6 col-md-4 col-lg-2 mb-4">

            <a href="{{ $child->isLeaf() ? route('shop.category.leaf.index', ['url' => $child->url, 'locale' => 'uk']) : route('shop.category.index', ['url' => $child->url, 'locale' => 'uk'])}}">

                <div class="card h-100">

                    @if($child->image)
                        <div class="card-body">
                            <img class="card-image w-100" src="/storage/{{ $child->image }}">
                        </div>
                    @endif

                    <div class="card-footer bg-white">
                        <h2 class="h5 text-gray-hover">{{ $child->name }}</h2>
                    </div>
                </div>

            </a>

        </div>

    @endforeach

</div>
