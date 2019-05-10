<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <span>Состояние</span>
        </div>
        <div class="col-sm-8">
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="is_new" id="newProductRadio" value="1" checked>
                <label class="custom-control-label" for="newProductRadio">Новый</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input class="custom-control-input" type="radio" name="is_new" id="secondHandProductRadio" value="0">
                <label class="custom-control-label" for="secondHandProductRadio">Б/У</label>
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="warranty">Гарантия (месяцы)</label>
        </div>
        <div class="col-sm-8">
            <input id="warranty" name="warranty" type="text" class="w-100" value="{{ old('warranty') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="weight">Вес (кг)</label>
        </div>
        <div class="col-sm-8">
            <input id="weight" name="weight" type="text" class="w-100" value="{{ old('weight') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="length">Длина (см)</label>
        </div>
        <div class="col-sm-8">
            <input id="length" name="length" type="text" class="w-100" value="{{ old('length') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="width">Ширина (см)</label>
        </div>
        <div class="col-sm-8">
            <input id="width" name="width" type="text" class="w-100" value="{{ old('width') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="height">Высота (см)</label>
        </div>
        <div class="col-sm-8">
            <input id="height" name="height" type="text" class="w-100" value="{{ old('height') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="volume">Объем (м<sup>3</sup>)</label>
        </div>
        <div class="col-sm-8">
            <input id="volume" name="volume" type="text" class="w-100" value="{{ old('volume') }}">
        </div>
    </div>

</div>