<ul class="list-group-flush">

    @if($user->avatar)
        <li class="list-group-item">
            <div class="row w-100">
                <div class="col-6 col-lg-4">
                    <strong>Фото</strong>
                </div>
                <div class="col-6 col-lg-8">
                    <img class="table-large-image img-fluid" src="/storage/{{ $user->avatar }}">
                </div>
            </div>
        </li>
    @endif

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Имя</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $user->name }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>E-Mail</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $user->email }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Телефон</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $user->phone }}
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Ценовая группа</strong>
            </div>
            <div class="col-6 col-lg-8">
                <span class="user-price-group">{{ $user->price_group }}</span>
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row w-100">
            <div class="col-6 col-lg-4">
                <strong>Баланс</strong>
            </div>
            <div class="col-6 col-lg-8">
                {{ $user->balance }}
            </div>
        </div>
    </li>

</ul>
