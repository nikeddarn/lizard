<div id="product-filter-input-template">

    <div class="card p-5 mb-5 product-filter-item d-none">

        <button type="button" class="btn btn-danger product-filter-item-delete mr-md-2 mt-md-2" data-toggle="tooltip"
                title="Удалить фильтр">
            <i class="fa fa-trash-o"></i>&nbsp;
        </button>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Фильтр</label>
            </div>
            <div class="col-sm-8">
                <select name="filter_id[]" class="w-100 filter-id-select">
                    <option value="0" selected>Выберете фильтр</option>
                    @foreach($filters as $filter)
                        <option value="{{ $filter->id }}">{{ $filter->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

</div>