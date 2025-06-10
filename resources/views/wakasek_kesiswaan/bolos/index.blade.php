@extends('layouts.navbarkesis')

@section('content')
<div class="container">
    <h3>Monitoring Siswa Bolos</h3>

    <form method="GET" action="{{ route('wakasek.kesiswaan.bolos.index') }}" class="mb-2">
        <div class="input-group" style="max-width: 350px;">
            <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" class="form-control mb-2">
            <button type="submit" class="btn btn-hijau-sias">Cari</button>
        </div>
        
    </form>

    <div class="table-responsive table-wrapper">
        <table class="table table-bordered mb-0">
            <thead class="thead-secondary">
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bolos as $b)
                    <tr>
                        <td>{{ $b->siswa->nama }}</td>
                        <td>{{ $b->siswa->kelas->kelas }}-{{ $b->siswa->kelas->sub_kelas }}</td>
                        <td>{{ $b->jadwal->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $b->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data bolos pada tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
