<form id="change-password-form" role="form" method="POST" action="{{ route('user.password.change') }}">
    @csrf
    <input type="hidden" name="locale" value="uk">

    <div class="row my-4 custom-form">

        <div class="col-12 col-md-8 col-lg-6">

            <div class="form-group pb-4{{ $errors->has('old_password') ? ' has-error' : '' }}">
                <label class="bold text-gray-hover" for="old_password">Старий пароль</label>
                <div>
                    <input id="old_password" type="password" class="form-control" name="old_password" required>
                    @if ($errors->has('old_password'))
                        <span class="help-block">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="bold text-gray-hover" for="password">Пароль</label>
                <div>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="bold text-gray-hover" for="password-confirm">Повторіть пароль</label>
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

        </div>

    </div>

    <div class="form-group my-4">
        <button type="submit" class="btn btn-primary">
            <span class="ml-2">Змінити пароль</span>
        </button>
    </div>
</form>
