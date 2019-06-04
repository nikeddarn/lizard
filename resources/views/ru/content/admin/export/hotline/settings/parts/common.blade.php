<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="firmName">Название фирмы (Firm Name)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="firmName" class="form-control" type="text" name="firm_name"
                       value="{{ old('firm_name', $settings['firm_name']) }}">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="firmId">Идентификатор фирмы (Firm Id)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="firmId" class="form-control" type="text" name="firm_id"
                       value="{{ old('firm_id', $settings['firm_id']) }}">
            </div>
        </div>
    </div>

</div>
