@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Tambah Guru Baru</h1>
        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Guru
        </a>
    </div>

    <div class="form-card">
        <h4 class="fw-bold mb-4">Formulir Data Guru</h4>
        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama guru" style="width: 100%;" required>
            </div>

            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" id="nip" name="nip" class="form-control" placeholder="Masukkan NIP" style="width: 100%;" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" style="width: 100%;" required>
            </div>

            <div class="mb-4">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" style="width: 100%;" required>
                    <option value="guru_pengajar">Guru Pengajar</option>
                    <option value="guru_bk">Guru BK</option>
                    <option value="wakasek_kesiswaan">Wakasek Kesiswaan</option>
                    <option value="wakasek_kurikulum">Wakasek Kurikulum</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-green">
                    <i class="bi bi-check-circle-fill me-2"></i>Simpan Guru
                </button>
            </div>

        </form>
    </div>
</div>

@endsection