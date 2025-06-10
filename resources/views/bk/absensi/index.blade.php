@extends('layouts.navbarbk')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Absensi Kelas {{ $kelas->kelas }}-{{ $kelas->sub_kelas }} Tanggal {{ $tanggal }}</h3>
    <a href="{{ url()->previous() }}" class="btn btn-hijau-sias">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="GET" action="{{ route('bk.kelas.tanggal', [$kelas->kelas_id, 'tanggal' => now()->toDateString()]) }}" onsubmit="this.action += this.tanggal.value;">
    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control mb-3" >
</form>

<div class="table-responsive table-wrapper mb-2">
    <table class="table table-bordered mb-0">
        <thead class="thead-secondary">
        <tr>
            <th>Nama</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Bukti</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($siswa as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td>{{ ucfirst($s->absensiSiswa->first()->status ?? '-') }}</td>
                <td>{{ $s->absensiSiswa->first()->keterangan ?? '-' }}</td>
                <td>
                    @if($s->absensiSiswa->first()?->bukti_keterangan)
                        <a href="{{ asset('storage/' . $s->absensiSiswa->first()->bukti_keterangan) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($s->absensiSiswa->first())
                        <a href="{{ route('bk.absensi.edit', $s->absensiSiswa->first()->absensi_siswa_id) }}" class="btn btn-sm btn-warning">Edit</a>
                    @else
                        <a href="{{ route('bk.absensi.create', [$s->siswa_id, $tanggal]) }}" class="btn btn-sm btn-primary">Isi Absen</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
