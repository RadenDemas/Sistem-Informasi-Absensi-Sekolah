@extends('layouts.guest')

@section('content')
<div class="login-card" style="max-width: 450px;">
    <h4 class="fw-bold mb-3">Reset Password</h4>
    <p class="text-muted mb-4">Silakan masukkan NIP dan password baru Anda.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('forgot-password.save') }}">
        @csrf

        <div class="mb-3 text-start">
            <label for="nip" class="form-label fw-bold">NIP</label>
            <input id="nip" type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}" required autofocus>
            @error('nip')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3 text-start">
            <label for="password" class="form-label fw-bold">Password Baru</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3 text-start">
            <label for="password-confirm" class="form-label fw-bold">Konfirmasi Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>


        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-green">
                {{ __('Reset Password') }}
            </button>
        </div>

        <div class="mt-4 text-center">
             <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke Halaman Login</a>
        </div>
    </form>
</div>
@endsection