@if($filterProducts->count())

    <div class="card py-2 px-1 p-lg-5 mb-5">
        <h4 class="mb-5 text-center">Продукты фильтра</h4>


        <table class="table">

            <tbody>

            @foreach($filterProducts as $product)

                <tr>

                    <td>{{ $product->name }}</td>

                    <td class="text-right">

                        <form class="d-inline-block delete-item-form ml-0 ml-md-2"
                              action="{{ route('admin.filter.products.destroy', ['id' => $product->id]) }}"
                              method="post">
                            @csrf
                            <input type="hidden" name="_method" value="delete"/>
                            <input type="hidden" name="id" value="{{ $filter->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить продукт из фильтра">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>
                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>


        @if($filterProducts->links())
            <div class="col-lg-12 my-4 items-pagination">{{$filterProducts->links()}}</div>
        @endif

    </div>
@endif
