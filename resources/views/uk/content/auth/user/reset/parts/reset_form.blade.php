<div class="row justify-content-center">
    <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4">
        <div class="card card-login-repair">
            <div class="card-body">
                <form class="form-light-background-input" method="post" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group text-center">
                        <h3 class="bold text-gray-hover">Переустановити пароль</h3>
                        @if (session('status'))
                            <em class="text-info">{{ session('status') }}</em>
                        @endif
                    </div>
                    <div class="form-group">
                              <span class="input-icon">
                                    <i class="svg-icon text-gray-hover" data-feather="mail"></i>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                           placeholder="Email адреса">
                              </span>
                        @if ($errors->has('email') && session()->get('auth_method') === 'login')
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                            <span class="input-icon">
                                <i class="svg-icon" data-feather="lock"></i>
                                <input type="password" class="form-control" name="password" placeholder="Пароль">
                            </span>
                        @if ($errors->has('password'))
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                            <span class="input-icon">
                                <i class="svg-icon" data-feather="lock"></i>
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="Повторіть пароль">
                            </span>
                        @if ($errors->has('password'))
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-danger btn-block reset-done">ПЕРЕВСТАНОВИТИ</button>
                </form>
            </div>
        </div>
    </div>
</div>
