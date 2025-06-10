@extends('layouts.navbarbk')
@section('content')

<div class="form-container-card">
    <h3>Edit Absensi: {{ $absensi->siswa->nama }}</h3>

    <form action="{{ route('bk.absensi.update', $absensi->absensi_siswa_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status">Status:</label>
            <select name="status" class="form-select" style="width: 100%;" required>
                <option value="hadir" {{ $absensi->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="sakit" {{ $absensi->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin" {{ $absensi->status == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="alpha" {{ $absensi->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="keterangan">Keterangan:</label>
            <input type="text" name="keterangan" value="{{ $absensi->keterangan }}" class="form-control" style="width: 100%;" >
        </div>

        <div class="mb-3">
            <label for="bukti_keterangan">Bukti Keterangan(opsional)</label>
            <input type="file" name="bukti_keterangan" value="{{ $absensi->bukti_keterangan }}" class="form-control" style="width: 100%;">
        </div>
        
        <div class="d-flex justify-content-end gap-3" style="width: 100%;">
            <a href="{{ url()->previous() }}" class="btn btn-kuning-sias">Batal</a>
            <button type="submit" class="btn btn-hijau-sias">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
