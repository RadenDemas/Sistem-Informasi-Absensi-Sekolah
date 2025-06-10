@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Edit Data Guru</h1>
        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Guru
        </a>
    </div>

    <div class="form-card">
        <h4 class="fw-bold mb-4">Formulir Edit Data Guru</h4>
        <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" >
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" value="{{ $guru->nama }}" required style="width: 100%;">
            </div>

            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" id="nip" name="nip" class="form-control" value="{{ $guru->nip }}" required style="width: 100%;">
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Password Baru (Opsional)</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah" style="width: 100%;">
                <small class="form-text text-muted">Isi kolom ini hanya jika Anda ingin mengganti password guru.</small>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-hijau-sias">
                    <i class="bi bi-check-circle-fill me-2"></i>Update Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection