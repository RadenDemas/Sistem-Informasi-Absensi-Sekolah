@extends('layouts.navbarkesis')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Absensi Kelas {{ $kelas->kelas }}-{{ $kelas->sub_kelas }} Tanggal {{ $tanggal }}</h3>
    <a href="{{ url()->previous() }}" class="btn btn-hijau-sias">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="GET" class="mb-3" action="{{ route('wakasek.kesiswaan.kelas.tanggal', [$kelas->kelas_id, 'tanggal' => now()->toDateString()]) }}" onsubmit="this.action += this.tanggal.value;">
    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control mb-3">
    <!-- <button type="submit" class="btn btn-primary mt-2">Lihat</button> -->
</form>

<div class="table-responsive table-wrapper mb-2">
    <table class="table table-bordered mb-0">
        <thead class="thead-secondary">
        <tr>
            <th>Nama</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Bukti</th>
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
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
