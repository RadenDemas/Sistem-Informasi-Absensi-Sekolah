@extends('layouts.navbarguru')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Daftar Subkelas dari Kelas {{ $tingkat }}</h1>
        <a href="{{ route('guru.dashboard') }}" class="btn btn-hijau-sias">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="list-group">
        @forelse($subkelas as $k)
            <div class="list-group-item subclass-list-card d-flex justify-content-between align-items-center p-4 mb-3">
                <div>
                    <p class="text-muted mb-0">Kelas</p>
                    <h4 class="fw-bold mb-0">{{ $k->kelas }}-{{ $k->sub_kelas }}</h4>
                </div>
                <a href="{{ route('guru.absen.form', $k->kelas_id) }}" class="btn btn-kuning-sias">
                    <i class="bi bi-pencil-square me-2"></i>Isi Absensi
                </a>
            </div>
        @empty
             <div class="alert alert-warning">
                Tidak ada jadwal mengajar untuk tingkat kelas ini pada hari ini.
            </div>
        @endforelse
    </ul>
</div>

@endsection