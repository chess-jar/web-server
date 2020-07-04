@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <x-alert/>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @honeypot
                        @csrf
                        <fieldset>
                            <legend>{{ __('auth.register') }}</legend>
                            <div class="form-group">
                                <label for="username">{{ __('auth.username') }}</label>
                                <input type="text" class="form-control" id="username" value="{{ old('username') }}" name="username" required autocomplete="username" autofocus />
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('auth.email') }}</label>
                                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" name="email" required autocomplete="email" />
                                <small id="emailHelp" class="form-text text-muted">{{ __('auth.email_safe') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('passwords.password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password" />
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">{{ __('passwords.password_confirm') }}</label>
                                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-user-plus"></i> {{ __('auth.register') }}
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