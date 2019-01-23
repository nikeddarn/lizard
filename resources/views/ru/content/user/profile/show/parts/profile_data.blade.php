<div class="row justify-content-center">

    <div class="col-12">

        <ul class="list-group list-group-flush text-center">
            <li class="list-group-item">
                <strong class="float-left">Имя</strong>
                <span>{{ $userProfile->name }}</span>
            </li>
            <li class="list-group-item">
                <strong class="float-left">E-mail</strong>
                <span>{{ $userProfile->email }}</span>
            </li>
            <li class="list-group-item">
                <strong class="float-left">Телефон</strong>
                @if(!empty($userProfile->phone))
                    <span>{{ $userProfile->phone }}</span>
                @else
                    <span>Не указан</span>
                @endif
            </li>
        </ul>

    </div>

    <div class="col-12">

        <a href="{{ route('user.profile.edit') }}" class="btn btn-primary pull-right">
            <i class="fa fa-pencil"></i>&nbsp;Редактировать профиль</a>

    </div>

</div>
