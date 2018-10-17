<div class="card py-2 px-1 p-lg-5 mb-5">

    <h4 class="mb-5 text-center">Данные пользователя</h4>

    <ul class="list-group">

        @if($user->avatar)
            <li class="list-group-item">
                <div class="row">
                    <div class="col col-lg-4">
                        <strong>Фото</strong>
                    </div>
                    <div class="col col-lg-8">
                        <img class="table-large-image img-fluid" src="/storage/{{ $user->avatar }}">
                    </div>
                </div>
            </li>
        @endif

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Имя</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $user->name }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>E-Mail</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $user->email }}
                </div>
            </div>
        </li>

        <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Телефон</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $user->phone }}
                </div>
            </div>
        </li>

            <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Ценовая группа</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $user->price_group }}
                </div>
            </div>
        </li>

            <li class="list-group-item">
            <div class="row">
                <div class="col col-lg-4">
                    <strong>Баланс</strong>
                </div>
                <div class="col col-lg-8">
                    {{ $user->balance }}
                </div>
            </div>
        </li>

    </ul>

</div>