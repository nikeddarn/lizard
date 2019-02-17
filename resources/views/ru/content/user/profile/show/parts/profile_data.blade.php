<div class="table-responsive">

    <table class="table">

        <tbody>

        <tr>
            <td><strong>Имя</strong></td>
            <td  class="text-center">{{ $userProfile->name }}</td>
        </tr>

        <tr>
            <td><strong>E-mail</strong></td>
            <td class="text-center">{{ $userProfile->email }}</td>
        </tr>

        <tr>
            <td><strong>Телефон</strong></td>
            <td class="text-center">
                @if(!empty($userProfile->phone))
                    {{ $userProfile->phone }}
                @else
                    Не указан
                @endif
            </td>
        </tr>

        </tbody>

    </table>

</div>

<div class="my-4">
    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary pull-right">
        <i class="svg-icon-larger" data-feather="edit"></i>
        <span class="ml-2">Редактировать профиль</span>
    </a>
</div>
