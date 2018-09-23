<form id="registration-form" role="form" method="POST" action="/register">

    @csrf

    <div class="row">
        <div class="col-sm-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name">Ваше имя</label>
            <div>
                <input id="name" type="text" class="form-control" name="name" maxlength="64" value="{{ old('name') }}"
                       required
                       autofocus>
                @if ($errors->has('name'))
                    <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-6 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">E-Mail Адрес</label>
            <div>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                       autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-6 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password">Пароль</label>
            <div>
                <input id="password" type="password" class="form-control" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-6 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password-confirm">Повторите пароль</label>
            <div>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                       required>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
        </div>


        <div class="col-lg-12 form-group">
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-long-arrow-right"></i>
                    Зарегистрироваться
                </button>
            </div>
        </div>

    </div>
</form>

