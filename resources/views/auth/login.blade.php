@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="text-center">
    <div class="icon-wrapper bg-gradient-violet mb-3 mx-auto">
        <i class="bi bi-person-circle"></i>
    </div>
    <h3 class="fw-bold mb-3">Welcome Back</h3>
    <p class="text-light small mb-4 opacity-75">Log in to access your dashboard</p>
</div>

@if(session('status'))
    <div class="alert alert-success shadow-sm border-0 py-2 mb-3 text-center">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label text-light small">Email</label>
        <input id="email" type="email"
               class="form-control custom-input @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label text-light small">Password</label>
        <input id="password" type="password"
               class="form-control custom-input @error('password') is-invalid @enderror"
               name="password" required>
        @error('password')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
        <label class="form-check-label small text-light" for="remember_me">Remember Me</label>
    </div>

    <!-- Submit -->
    <div class="d-flex justify-content-between align-items-center">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-decoration-none small text-light opacity-75">
                Forgot password?
            </a>
        @endif
        <button type="submit" class="btn btn-premium">Log in</button>
    </div>
</form>

<style>
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
.bg-gradient-violet { background: linear-gradient(135deg, #6a11cb, #2575fc); }
</style>
@endsection
