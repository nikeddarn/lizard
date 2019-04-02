<div class="card-login">
    <div class="row">

        <!-- Login Form -->
        <div class="col-md-6 col-login">

            <div class="form-group text-center text-gray-hover mb-2">
                <h5 class="bold mb-0">Войдите</h5>
                <em>в ваш аккаунт</em>
            </div>
            <div class="form-group">
                  <span class="input-icon text-gray-hover">
                        <i class="svg-icon" data-feather="mail"></i>
                        <input type="email" class="form-control" name="login_email"
                               value="{{ old('login_email') }}"
                               placeholder="Email адрес">
                  </span>
                @if ($errors->has('login_email'))
                    <small class="text-danger">{{ $errors->first('login_email') }}</small>
                @endif
            </div>
            <div class="form-group">
                <span class="input-icon text-gray-hover">
                    <i class="svg-icon" data-feather="lock"></i>
                    <input type="password" class="form-control" name="login_password" placeholder="Пароль">
                </span>
                @if ($errors->has('login_password'))
                    <small class="text-danger">{{ $errors->first('login_password') }}</small>
                @endif
            </div>
            <div class="form-group d-flex justify-content-between">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="loginRemember"
                           name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label text-gray-hover" for="loginRemember">Запомнить
                        меня</label>
                </div>
                <u>
                    <a href="{{ route('password.request') }}" class="text-primary small">Забыли пароль ?</a>
                </u>
            </div>
        </div>

        <span class="or-divider text-gray-hover">ИЛИ</span>

        <!-- Register Form -->
        <div class="col-md-6 col-register">
            <div class="form-group text-center text-gray-hover">
                <h5 class="bold">Зарегистрируйтесь</h5>
            </div>
            <div class="form-group">
              <span class="input-icon text-gray-hover">
                    <i class="svg-icon" data-feather="mail"></i>
                    <input type="email" class="form-control" name="register_email"
                           value="{{ old('register_email') }}" placeholder="Email адрес">
              </span>
                @if ($errors->has('register_email'))
                    <small class="text-danger">{{ $errors->first('register_email') }}</small>
                @endif
            </div>
            <div class="form-group">
                <span class="input-icon text-gray-hover">
                    <i class="svg-icon" data-feather="lock"></i>
                    <input type="password" class="form-control" name="register_password" placeholder="Пароль">
                </span>
                @if ($errors->has('register_password'))
                    <small class="text-danger">{{ $errors->first('register_password') }}</small>
                @endif
            </div>
            <div class="form-group">
                <span class="input-icon text-gray-hover">
                    <i class="svg-icon" data-feather="lock"></i>
                    <input type="password" class="form-control" name="register_password_confirmation"
                           placeholder="Повторите пароль">
                </span>
                @if ($errors->has('register_password'))
                    <small class="text-danger">{{ $errors->first('register_password') }}</small>
                @endif
            </div>
        </div>

    </div>
</div>
