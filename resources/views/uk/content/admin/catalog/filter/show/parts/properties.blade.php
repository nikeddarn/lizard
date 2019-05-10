<div class="card py-2 px-1 p-lg-5 mb-5">
    <h4 class="mb-5 text-center">Свойства фильтра</h4>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Наименование (RU)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $filter->name_ru }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Наименование (UA)</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $filter->name_uk }}
                </div>
            </div>
        </li>
    </ul>
</div>