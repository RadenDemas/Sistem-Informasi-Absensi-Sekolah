@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Tambah Kelas Baru</h1>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
        </a>
    </div>

    <div class="form-card">
        <h4 class="fw-bold mb-4">Formulir Data Kelas</h4>
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="kelas" class="form-label">Tingkat Kelas</label>
                <input type="text" id="kelas" name="kelas" class="form-control" placeholder="Contoh: 7, 8, atau 9" style="width: 100%;" required>
            </div>

            <div class="mb-4">
                <label for="sub_kelas" class="form-label">Sub Kelas</label>
                <input type="text" id="sub_kelas" name="sub_kelas" class="form-control" placeholder="Contoh: A, B, atau 1, 2" style="width: 100%;" required>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-hijau-sias">
                    <i class="bi bi-check-circle-fill me-2"></i>Simpan Kelas
                </button>
            </div>

        </form>
    </div>
</div>

@endsection