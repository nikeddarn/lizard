@if($brands->count())
    <table id="categories-list-table" class="table">

        <tbody>

        @foreach($brands as $brand)

            <tr>

                <td><img class="table-image img-fluid" src="/storage/{{ $brand->image }}"></td>

                <td><strong>{{ $brand->name }}</strong></td>

                <td class="text-right">

                    <a href="{{ route('admin.brands.edit', ['id' => $brand->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                    <form class="d-inline-block brand-form ml-lg-2"
                          action="{{ route('admin.brands.destroy', ['id' => $brand->id]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $brand->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

@else

    <p>Нет ни одного бренда</p>

@endif