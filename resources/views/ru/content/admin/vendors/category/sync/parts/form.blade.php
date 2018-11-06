<form method="post" action="{{ route('vendor.category.link') }}" role="form">

    @csrf

    <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
    <input type="hidden" name="vendor_category_id" value="{{ $vendorCategory->id }}">

    <div class="card p-5 mb-5">

        <div class="row">

            <div class="col-sm-4">
                <label class="required" for="categories_id">Синхронизировать с категорией</label>
            </div>

            <div class="col-sm-6">
                <select id="categories_id" name="categories_id" class="selectpicker w-100">
                    @include('content.admin.vendors.category.sync.parts.select_category_options')
                </select>
            </div>

        </div>

    </div>

    <button type="submit" class="btn btn-primary">Синхронизировать категорию</button>

</form>


