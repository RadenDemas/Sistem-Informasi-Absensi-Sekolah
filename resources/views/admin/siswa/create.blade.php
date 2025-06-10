@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Tambah Siswa Baru</h1>
        <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
        </a>
    </div>

    <div class="form-card">
        <h4 class="fw-bold mb-4">Formulir Data Siswa</h4>
        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama siswa" required style="width: 100%;">
            </div>

            <div class="mb-3">
                <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                <input type="text" id="nis" name="nis" class="form-control" placeholder="Masukkan NIS siswa" required style="width: 100%;">
            </div>

            <div class="mb-4">
                <label for="kelas_id" class="form-label">Kelas</label>
                <select id="kelas_id" name="kelas_id" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Kelas --</option>
                    @foreach ($kelas as $k)
                        <option style="width: 100%;" value="{{ $k->kelas_id }}">{{ $k->kelas }} - {{ $k->sub_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-hijau-sias">
                    <i class="bi bi-check-circle-fill me-2"></i>Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection