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

    <h1 class="h2 mb-4 fw-bold">Dashboard Guru BK</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card text-white" style="background-color: #1E6042; border-radius: 20px;">
        <div class="card-body p-4">
            <div class="row gy-4 gy-md-0 align-items-center justify-content-center text-center">

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-person-circle" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Siswa</p>
                            <h4 class="fw-bold mb-0">{{$siswa}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-calendar2-check" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Hadir</p>
                            <h4 class="fw-bold mb-0">{{$hadir}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-car-front-fill" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Izin</p>
                            <h4 class="fw-bold mb-0">{{$izin}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-hospital" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Sakit</p>
                            <h4 class="fw-bold mb-0">{{$sakit}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-calendar2-x" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Tidak Masuk</p>
                            <h4 class="fw-bold mb-0">{{$alpha}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-person-walking" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Bolos</p>
                            <h4 class="fw-bold mb-0">{{$bolos}}</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

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
