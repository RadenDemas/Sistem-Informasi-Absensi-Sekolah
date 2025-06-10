@extends('layouts.navbarbk')

@section('content')

    <style>
        .subclass-card {
            border-radius: 15px;
            border: 1px solid #737373;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            background-color: #ffffff;
        }
        .subclass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Daftar Kelas {{ $kelas }}</h1>
        <a href="{{ route('bk.dashboard') }}" class="btn btn-hijau-sias">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row g-4">
        @forelse ($subKelasList as $kelasItem)
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('bk.kelas.tanggal', ['kelas_id' => $kelasItem->kelas_id, 'tanggal' => now()->toDateString()]) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm subclass-card">
                        <div class="card-body text-center p-4">
                            <p class="text-muted mb-2">Kelas</p>
                            <h3 class="card-title mb-0 fw-bold">{{ $kelasItem->kelas }}-{{ $kelasItem->sub_kelas }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">
                    Tidak ada data sub kelas yang tersedia untuk tingkat ini.
                </div>
            </div>
        @endforelse
    </div>

@endsection