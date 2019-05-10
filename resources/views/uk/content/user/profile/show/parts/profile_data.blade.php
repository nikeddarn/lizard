<div class="table-responsive my-4">

    <table class="table table-borderless">

        <tbody>

        <tr>
            <td><strong>Ім'я</strong></td>
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
                    Не вказаний
                @endif
            </td>
        </tr>

        </tbody>

    </table>

</div>

<div class="my-4">
    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary pull-right">
        <i class="svg-icon-larger" data-feather="edit"></i>
        <span class="ml-2">Редагувати профіль</span>
    </a>
</div>
