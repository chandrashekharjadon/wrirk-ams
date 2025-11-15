@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="text-center mb-4">Verify Your Email Address</h2>

        <p class="mb-4 text-muted">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? 
            If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="d-flex justify-content-between mt-4">
            <!-- Resend Verification Email -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-underline">Log Out</button>
            </form>
        </div>
    </div>
</div>
@endsection
