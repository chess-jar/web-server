@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <x-alert/>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @honeypot
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <fieldset>
                            <legend>{{ __('templating.password_reset') }}</legend>
                            <div class="form-group">
                                <label for="email">{{ __('templating.email') }}</label>
                                <input type="email" class="form-control" id="email" value="{{ old('email') }}" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus />
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('templating.password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password" />
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">{{ __('templating.password_confirm') }}</label>
                                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-lock"></i> {{ __('templating.password_reset') }}
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