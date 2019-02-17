<form id="change-password-form" role="form" method="POST" action="{{ route('user.password.change') }}">

    @csrf

    <div class="row justify-content-md-center">

        <div class="col-sm-8 form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
            <label for="old_password">Старый пароль</label>
            <div>
                <input id="old_password" type="password" class="form-control" name="old_password" required>
                @if ($errors->has('old_password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-8 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
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

        <div class="col-sm-8 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password-confirm">Повторите пароль</label>
            <div>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-sm-8 form-group my-4">
            <div class="pull-right">
                <button type="submit" class="btn btn-primary">
                    <i class="svg-icon-larger" data-feather="save"></i>
                    <span class="ml-2">Изменить пароль</span>
                </button>
            </div>
        </div>

    </div>
</form>
