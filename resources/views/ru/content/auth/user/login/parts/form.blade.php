<div class="col-sm-10 col-md-11 col-lg-9 col-xl-8">
    <div class="card card-login">
        <div class="card-body">
            <div class="row">

                <!-- Login Form -->
                <div class="col-md-6 col-login">
                    <form class="form-light-background-input" id="login-form" role="form" method="POST"
                          action="{{ url('/login') }}">
                        @csrf

                        <div class="form-group text-center text-gray-hover mb-2">
                            <h3 class="bold mb-0">Войдите</h3>
                            <em>в ваш аккаунт</em>
                        </div>
                        <div class="form-group">
                              <span class="input-icon text-gray-hover">
                                    <i class="svg-icon" data-feather="mail"></i>
                                    <input type="email" class="form-control" name="email" value="{{ session()->get('auth_method') === 'login' ? old('email') : '' }}"
                                           placeholder="Email адрес">
                              </span>
                            @if ($errors->has('email') && session()->get('auth_method') === 'login')
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <span class="input-icon text-gray-hover">
                                <i class="svg-icon" data-feather="lock"></i>
                                <input type="password" class="form-control" name="password" placeholder="Пароль">
                            </span>
                            @if ($errors->has('password') && session()->get('auth_method') === 'login')
                                <small class="text-danger">{{ $errors->first('password') }}</small>
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
                                <a href="{{ route('password.request', ['locale' => config('app.canonical_locale') === 'ru' ? '' : 'ru']) }}" class="text-primary small">Забыли пароль ?</a>
                            </u>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">ВОЙТИ</button>
                    </form>
                </div>
                <!-- /Login Form -->

                <span class="or-divider text-gray-hover">ИЛИ</span>

                <!-- Register Form -->
                <div class="col-md-6 col-register">
                    <form class="form-light-background-input" id="registration-form" role="form" method="POST"
                          action="{{ url('/register') }}">
                        @csrf
                        <div class="form-group text-center text-gray-hover">
                            <h3 class="bold">Зарегистрируйтесь</h3>
                        </div>
                        <div class="form-group">
                          <span class="input-icon text-gray-hover">
                                <i class="svg-icon" data-feather="user"></i>
                                <input type="text" class="form-control" name="name" value="{{ session()->get('auth_method') === 'register' ? old('name') : '' }}"
                                       placeholder="Ваше имя">
                          </span>
                            @if ($errors->has('name') && session()->get('auth_method') === 'register')
                                <small class="text-danger">{{ $errors->first('name') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                          <span class="input-icon text-gray-hover">
                                <i class="svg-icon" data-feather="mail"></i>
                                <input type="email" class="form-control" name="email" value="{{ session()->get('auth_method') === 'register' ? old('email') : '' }}"
                                       placeholder="Email адрес">
                          </span>
                            @if ($errors->has('email') && session()->get('auth_method') === 'register')
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <span class="input-icon text-gray-hover">
                                <i class="svg-icon" data-feather="lock"></i>
                                <input type="password" class="form-control" name="password" placeholder="Пароль">
                            </span>
                            @if ($errors->has('password') && session()->get('auth_method') === 'register')
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <span class="input-icon text-gray-hover">
                                <i class="svg-icon" data-feather="lock"></i>
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="Повторите пароль">
                            </span>
                            @if ($errors->has('password') && session()->get('auth_method') === 'register')
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">ЗАРЕГИСТРИРОВАТЬСЯ</button>
                    </form>
                </div>
                <!-- /Register Form -->

            </div>
        </div>
    </div>
</div>
