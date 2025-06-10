@extends('layouts.navbarbk')

@section('content')

    <style>
        .class-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .class-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }
    </style>

    <h1 class="h2 mb-4 fw-bold">Monitoring Absensi Siswa</h1>
    <h4 class="mt-5 mb-3">Pilih Tingkat Kelas</h4>
    <div class="row g-4">
        @isset($tingkatKelas)
            @forelse ($tingkatKelas as $tingkat)
                <div class="col-md-4">
                    <a href="{{ route('bk.kelas.listSub', $tingkat->kelas) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm class-card" style="border-radius: 15px; border:0;">
                            <div class="card-body d-flex align-items-center p-4">
                                <h5 class="card-title mb-0 fw-bold">Kelas {{ $tingkat->kelas }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">
                        Tidak ada data tingkat kelas yang tersedia saat ini.
                    </div>
                </div>
            @endforelse
        @endisset
    </div>

@endsection
