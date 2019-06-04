<div class="table-responsive">
    <table class="table table-striped">

        <tbody>


        @foreach($attributes as $attribute)

            <tr>


                <td>{{ $attribute->name }}</td>

                <td class="d-flex justify-content-end align-items-end">

                    <a href="{{ route('admin.attributes.show', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="eye"></i>
                    </a>

                    <a href="{{ route('admin.attributes.edit', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary ml-1">
                        <i class="svg-icon-larger" data-feather="edit"></i>
                    </a>

                    <div class="d-flex flex-row mx-2">

                        <div>
                            <a href="{{ route('admin.attributes.filter.hide', ['id' => $attribute->id]) }}"
                               data-toggle="tooltip"
                               title="Сделать фильтр скрытым"
                               class="attribute-property-change attribute-property-off btn btn-primary{{ $attribute->showable ? ' d-inline-block' : ' d-none' }}">
                                <i class="svg-icon-larger" data-feather="filter"></i>
                            </a>

                            <a href="{{ route('admin.attributes.filter.show', ['id' => $attribute->id]) }}"
                               data-toggle="tooltip"
                               title="Сделать фильтр видимым"
                               class="attribute-property-change attribute-property-on btn btn-outline-primary checkbox-item-button{{ $attribute->showable ? ' d-none' : ' d-inline-block' }}">
                                <i class="svg-icon-larger" data-feather="filter"></i>
                            </a>
                        </div>

                        <div class="ml-1">
                            <a href="{{ route('admin.attributes.index.disallow', ['id' => $attribute->id]) }}"
                               data-toggle="tooltip"
                               title="Запретить индексацию для роботов"
                               class="attribute-property-change attribute-property-off btn btn-primary checkbox-item-button{{ $attribute->indexable ? ' d-inline-block' : ' d-none' }}">
                                <i class="svg-icon-larger" data-feather="search"></i>
                            </a>

                            <a href="{{ route('admin.attributes.index.allow', ['id' => $attribute->id]) }}"
                               data-toggle="tooltip"
                               title="Разрешить индексацию для роботов"
                               class="attribute-property-change attribute-property-on btn btn-outline-primary checkbox-item-button{{ $attribute->indexable ? ' d-none' : ' d-inline-block' }}">
                                <i class="svg-icon-larger" data-feather="search"></i>
                            </a>
                        </div>

                    </div>

                    <form class="d-inline-block attribute-form"
                          action="{{ route('admin.attributes.destroy', ['id' => $attribute->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="hidden" name="id" value="{{ $attribute->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="svg-icon-larger" data-feather="trash-2"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>
</div>
