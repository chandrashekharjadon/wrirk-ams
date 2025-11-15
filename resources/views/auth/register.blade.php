@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="text-center mb-4">
    <div class="icon-wrapper bg-gradient-violet-green mb-3 mx-auto">
        <i class="bi bi-person-plus-fill"></i>
    </div>
    <h3 class="fw-bold mb-2">Create an Account</h3>
    <p class="text-light small opacity-75">
        Join us and get access to your employee dashboard.
    </p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="mb-3 text-start">
        <label for="name" class="form-label text-light small">Full Name</label>
        <input id="name" type="text" class="form-control custom-input @error('name') is-invalid @enderror"
               name="name" value="{{ old('name') }}" required autofocus>
        @error('name')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="mb-3 text-start">
        <label for="email" class="form-label text-light small">Email Address</label>
        <input id="email" type="email" class="form-control custom-input @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required>
        @error('email')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3 text-start">
        <label for="password" class="form-label text-light small">Password</label>
        <input id="password" type="password" class="form-control custom-input @error('password') is-invalid @enderror"
               name="password" required>
        @error('password')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3 text-start">
        <label for="password_confirmation" class="form-label text-light small">Confirm Password</label>
        <input id="password_confirmation" type="password"
               class="form-control custom-input @error('password_confirmation') is-invalid @enderror"
               name="password_confirmation" required>
        @error('password_confirmation')
            <span class="invalid-feedback d-block small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Employee ID -->
    <div class="mb-3 text-start">
        <label for="employee_id" class="form-label text-light small">Employee ID</label>
        <input type="text" name="employee_id" class="form-control custom-input" value="{{ old('employee_id') }}" required>
    </div>

    <!-- Role -->
    <div class="mb-3 text-start">
        <label for="role" class="form-label text-light small">Role</label>
        <select name="role" class="form-select custom-input text-white" required>
            <option value="employee" @selected(old('role') == 'employee')>Employee</option>
            <option value="hr" @selected(old('role') == 'hr')>HR</option>
            <option value="admin" @selected(old('role') == 'admin')>Admin</option>
        </select>
    </div>

    <!-- Footer Buttons -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('login') }}" class="text-decoration-none small text-light opacity-75">
            Already registered?
        </a>
        <button type="submit" class="btn btn-premium">Register</button>
    </div>
</form>

<style>
/* Icon styling for top visual */
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
.bg-gradient-violet-green {
    background: linear-gradient(135deg, #36d1dc, #5b86e5);
}

/* Make select match input fields */
.custom-input {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    color: #fff;
    padding: 10px 14px;
}
.custom-input:focus {
    background: rgba(255,255,255,0.12);
    border-color: #6a11cb;
    box-shadow: 0 0 0 0.25rem rgba(106,17,203,0.25);
    color: #fff;
}
.form-select.custom-input option {
    background-color: #2d1b69;
    color: #fff;
}
</style>
@endsection
