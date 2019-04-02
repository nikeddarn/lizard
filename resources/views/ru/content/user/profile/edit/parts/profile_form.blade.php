<form id="change-profile-form" role="form" method="POST" action="{{ route('user.profile.save') }}"
      enctype="multipart/form-data">

    @csrf

    <div class="row my-4 custom-form">

        <div class="col-sm-12 col-md-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="bold text-gray-hover" for="name">Ваше имя</label>
            <div>
                <input id="name" type="text" class="form-control" name="name"
                       value="{{ old('name', $userProfile->name) }}" required>
                @if ($errors->has('name'))
                    <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-12 col-md-6 form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
            <label class="bold text-gray-hover" for="avatar">Аватар пользователя</label>
            <div>
                @include('elements.input_image.index', ['inputFileFieldName' => 'avatar'])
                @if ($errors->has('avatar'))
                    <span class="help-block">
                    <strong>{{ $errors->first('avatar') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-12 col-md-6 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="bold text-gray-hover" for="email">E-Mail Адрес</label>
            <div>
                <input id="email" type="email" class="form-control" name="email"
                       value="{{ old('email', $userProfile->email) }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-12 col-md-6 form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
            <label class="bold text-gray-hover" for="phone">Номер Телефона</label>
            <div>
                <input id="phone" type="text" class="form-control" name="phone"
                       value="{{ old('phone', $userProfile->phone) }}">
                @if ($errors->has('phone'))
                    <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
                @endif
            </div>
        </div>

    </div>

    <div class="my-4">
        <button type="submit" class="btn btn-primary">
            <span class="ml-2">Обновить профиль</span>
        </button>
    </div>

</form>
