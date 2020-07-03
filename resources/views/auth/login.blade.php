@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <x-alert/>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @honeypot
                        @csrf
                        <fieldset>
                            <legend>{{ __('auth.login') }}</legend>
                            <div class="form-group">
                                <label for="username">{{ __('auth.username') }}</label>
                                <input type="text" class="form-control" id="username" value="{{ old('username') }}" name="username" required autocomplete="username" autofocus />
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('passwords.password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" required autocomplete="current-password" />
                                @if (Route::has('password.request'))
                                <small id="passwordHelp" class="form-text text-muted">{{ __('passwords.forgotten_password') }} <a href="{{ route('password.request') }}">{{ __('auth.click_me') }}</a></small>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">{{ __('auth.remember') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-sign-in-alt"></i> {{ __('auth.login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection