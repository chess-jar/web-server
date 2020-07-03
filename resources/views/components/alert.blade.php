<div role="alert">
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ __('auth.error') }}</strong> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ __('auth.error') }}</strong> {{ $errors->first() }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
</div>
@endif
@if (session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>{{ __('auth.warning') }}</strong> {{ session('warning') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
</div>
@endif
@if (session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <strong>{{ __('auth.info') }}</strong> {{ session('info') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
</div>
@endif
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ __('auth.success') }}</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
</div>
@endif
@if (session('status'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ __('auth.success') }}</strong> {{ session('status') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times"></i></span>
    </button>
</div>
@endif
</div>