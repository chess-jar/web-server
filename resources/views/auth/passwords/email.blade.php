@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <x-alert/>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        @honeypot
                        @csrf
                        <fieldset>
                            <legend>{{ __('passwords.password_reset') }}</legend>
                            <div class="form-group">
                                <label for="email">{{ __('auth.email') }}</label>
                                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" name="email" required autocomplete="email" autofocus />
                                <small id="emailHelp" class="form-text text-muted">{{ __('auth.email_reset_text') }}</small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-paper-plane"></i> {{ __('passwords.send_reset_link') }}
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