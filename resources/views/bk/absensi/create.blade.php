@extends('layouts.navbarbk')
@section('content')

<div class="form-container-card">
    <h3>Tambah Absensi untuk {{ $siswa->nama }}</h3>

    <form action="{{ route('bk.absensi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="siswa_id" value="{{ $siswa->siswa_id }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="mb-3">
            <label>Status:</label>
            <select name="status" class="form-select" style="width: 100%;">
                <option value="hadir">Hadir</option>
                <option value="sakit">Sakit</option>
                <option value="izin">Izin</option>
                <option value="alpha">Alpha</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Keterangan:</label>
            <input type="text" name="keterangan" class="form-control" style="width: 100%;" placeholder="Masukkan keterangan (opsional)">
        </div>

        <div class="mb-3">
            <label>Bukti Keterangan (opsional):</label>
            <input type="file" name="bukti_keterangan" class="form-control" style="width: 100%;">
        </div>

         <div class="d-flex justify-content-end gap-3" style="width: 100%;">
            <a href="{{ url()->previous() }}" class="btn btn-kuning-sias">Batal</a>
            <button type="submit" class="btn btn-hijau-sias">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
