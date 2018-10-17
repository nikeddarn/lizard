<div class="card py-2 px-1 p-lg-5 mb-5">
    <h4 class="mb-5 text-center">Фильтры категории</h4>

    @if($category->filters->count())

        <table class="table">

            <tbody>

            @foreach($category->filters as $filter)

                <tr>

                    <td>{{ $filter->name }}</td>


                    <td class="text-right">

                        <form class="d-inline-block category-filter-delete-form"
                              action="{{ route('admin.categories.filter.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="categories_id" value="{{ $category->id }}">
                            <input type="hidden" name="filters_id" value="{{ $filter->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить фильтр категории">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>

                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>

    @endif

    <div class="col-lg-12 my-4 text-right">
        <a href="{{route('admin.categories.filter.create', ['id' => $category->id])}}" class="btn btn-primary">
            <i class="fa fa-plus"></i>&nbsp;
            <span>Добавить фильтр категории</span>
        </a>
    </div>

</div>
