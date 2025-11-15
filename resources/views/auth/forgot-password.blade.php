@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="text-center mb-4">
    <div class="icon-wrapper bg-gradient-violet mb-3 mx-auto">
        <i class="bi bi-envelope-paper-heart"></i>
    </div>
    <h3 class="fw-bold mb-2">Forgot Password?</h3>
    <p class="text-light small opacity-75">
        Don’t worry — just enter your email, and we’ll send you a reset link.
    </p>
</div>

@if(session('status'))
    <div class="alert alert-success shadow-sm border-0 py-2 mb-3 text-center">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email Address -->
    <div class="mb-3 text-start">
        <label for="email" class="form-label text-light small">Email Address</label>
        <input id="email" type="email"
               class="form-control custom-input @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('login') }}" class="text-decoration-none small text-light opacity-75">
            <i class="bi bi-arrow-left"></i> Back to login
        </a>
        <button type="submit" class="btn btn-premium">Send Reset Link</button>
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

/* Small success alert styling */
.alert-success {
    background: rgba(46, 213, 115, 0.15);
    border: 1px solid rgba(46, 213, 115, 0.25);
    color: #a7ffcb;
    border-radius: 8px;
    font-size: 0.9rem;
}
</style>
@endsection
