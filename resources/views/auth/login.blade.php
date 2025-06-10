@extends('layouts.guest')

@section('content')
<div class="login-card">
    <img src="{{ asset('storage/picture/logo.png') }}" alt="Logo SIAS">
    
    <h4>Selamat Datang di SIAS!</h4>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- NIP -->
        <div class="mb-3 text-start">
            <label for="nip" class="form-label fw-bold">NIP</label>
            <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required autofocus>
            @error('nip')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <label for="password" class="form-label fw-bold">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 text-start">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ingat Saya
                </label>
            </div>
        </div>

        <!-- Submit -->
        <div class="mb-3">
            <button type="submit" class="btn btn-green w-100">Masuk</button>
        </div>

        <!-- Forgot password link -->
        @if (Route::has('password.request'))
            <a class="forgot-password text-decoration-none" href="{{ route('forgot-password') }}">
                {{ __('Lupa Password') }}
            </a>
        @endif
    </form>
</div>
@endsection