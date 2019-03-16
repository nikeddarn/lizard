<div class="card p-5 mb-4">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="header_phone1">Телефон 1</label>
        </div>
        <div class="col-sm-8">
            <input id="header_phone1" name="header_phone[]" type="text" class="p-1" value="{{ $headerPhones[0] }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="header_phone2">Телефон 2</label>
        </div>
        <div class="col-sm-8">
            <input id="header_phone2" name="header_phone[]" type="text" class="p-1" value="{{ $headerPhones[1] }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="header_phone3">Телефон 3</label>
        </div>
        <div class="col-sm-8">
            <input id="header_phone3" name="header_phone[]" type="text" class="p-1" value="{{ $headerPhones[2] }}">
        </div>
    </div>

</div>
