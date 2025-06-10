@extends('layouts.navbarkepsek')

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

    <h1 class="h2 mb-4 fw-bold">Dashboard Kepala Sekolah</h1>
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
                            <h4 class="fw-bold mb-0">{{$hadirSiswa}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-car-front-fill" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Izin</p>
                            <h4 class="fw-bold mb-0">{{$izinSiswa}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-hospital" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Sakit</p>
                            <h4 class="fw-bold mb-0">{{$sakitSiswa}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-calendar2-x" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Tidak Masuk</p>
                            <h4 class="fw-bold mb-0">{{$alphaSiswa}}</h4>
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
    <div class="mt-5 mb-3">
    <div class="card text-white" style="background-color: #1E6042; border-radius: 20px;">
        <div class="card-body p-4">
            <div class="row gy-4 gy-md-0 align-items-center justify-content-center text-center">

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-person-circle" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Guru</p>
                            <h4 class="fw-bold mb-0">{{$guru}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-calendar2-check" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Hadir</p>
                            <h4 class="fw-bold mb-0">{{$hadirGuru}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-car-front-fill" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Izin</p>
                            <h4 class="fw-bold mb-0">{{$izinGuru}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-hospital" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Sakit</p>
                            <h4 class="fw-bold mb-0">{{$sakitGuru}}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex align-items-center justify-content-center p-2">
                        <i class="bi bi-calendar2-x" style="font-size: 2.8rem;"></i>
                        <div class="ms-3">
                            <p class="mb-0 small">Tidak Masuk</p>
                            <h4 class="fw-bold mb-0">{{$alphaGuru}}</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    <div class="mt-5">
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('kepala.sekolah.kelas') }}" class="btn btn-lg btn-light border">Monitoring Siswa</a>
            <a href="{{ route('kepala.sekolah.monitoring.guru') }}" class="btn btn-lg btn-light border">Monitoring Guru</a>
            <a href="{{ route('kepala.sekolah.bolos.index') }}" class="btn btn-lg btn-light border">Monitoring Siswa Bolos</a>
            <a href="{{ route('kepala.sekolah.rekap.data.siswa') }}" class="btn btn-lg btn-light border">Rekap Data Siswa</a>
            <a href="{{ route('kepala.sekolah.rekap.data.guru') }}" class="btn btn-lg btn-light border">Rekap Data Guru</a>
        </div>
    </div>

@endsection
