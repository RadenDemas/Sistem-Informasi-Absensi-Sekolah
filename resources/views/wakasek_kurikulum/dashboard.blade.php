@extends('layouts.navbarkuri')

@section('content')

<style>
    @media (min-width: 768px) {
        .border-start-md-custom {
            border-left: 1px solid rgba(255, 255, 255, 0.3);
        }
    }
</style>

<h1 class="h2 mb-4 fw-bold">Dashboard Waka Kurikulum</h1>
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
        </div>
    </div>
</div>
<div class="mt-5">
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('wakasek.kurikulum.absen.guru') }}" class="btn btn-lg btn-light border">Absen Guru</a>
            <a href="{{ route('wakasek.kurikulum.monitoring.guru') }}" class="btn btn-lg btn-light border">Monitoring Guru</a>
            <a href="{{ route('wakasek.kurikulum.rekap.data') }}" class="btn btn-lg btn-light border">Rekap Data</a>
        </div>
</div>
@endsection
