<form method="post" action="{{ route('vendor.category.link') }}" role="form">

    @csrf

    <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
    <input type="hidden" name="vendor_own_category_id" value="{{ $vendorCategory->id }}">

    <div class="card p-5 mb-5">

        <div class="row">

            <div class="col-sm-4">
                <label class="required" for="categories_id">Синхронизировать с категорией</label>
            </div>

            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('categories_id') ? ' has-error' : '' }}">
                    <select id="categories_id" name="categories_id" class="selectpicker w-100">
                        @include('content.admin.vendors.category.sync.parts.select_category_options')
                    </select>
                    @if ($errors->has('categories_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('categories_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <div class="card p-5 mb-5">

        <div class="row">

            <div class="col-sm-4">
                <label for="auto_add_new_products">Включить автодобавление новых продуктов</label>
            </div>

            <div class="col-sm-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="auto_add_new_products"
                           name="auto_add_new_products">
                    <label class="custom-control-label empty-checkbox-label" for="auto_add_new_products"></label>
                </div>
            </div>

        </div>

    </div>

    <button type="submit" class="btn btn-primary">Синхронизировать категорию</button>

</form>


