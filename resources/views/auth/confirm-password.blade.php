@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div class="text-center mb-4">
    <div class="icon-wrapper bg-gradient-violet-red mb-3 mx-auto">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    <h3 class="fw-bold mb-2">Confirm Password</h3>
    <p class="text-light small opacity-75">
        This is a secure area of the application.<br>
        Please confirm your password before continuing.
    </p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <!-- Password -->
    <div class="mb-3 text-start">
        <label for="password" class="form-label text-light small">Password</label>
        <input id="password" type="password"
               class="form-control custom-input @error('password') is-invalid @enderror"
               name="password" required autocomplete="current-password">
        @error('password')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('login') }}" class="text-decoration-none small text-light opacity-75">
            <i class="bi bi-arrow-left"></i> Back to login
        </a>
        <button type="submit" class="btn btn-premium">Confirm</button>
    </div>
</form>

<style>
/* Icon styling consistent with dashboard & login */
.icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: inset 0 -6px 18px rgba(255,255,255,0.02);
}
.bg-gradient-violet-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }

/* Input and button styles already defined in layout */
</style>
@endsection
