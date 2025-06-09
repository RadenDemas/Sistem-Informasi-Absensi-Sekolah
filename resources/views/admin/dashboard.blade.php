@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Dashboard Admin</h2>

        <div class="mt-4">
            <a href="{{ route('admin.guru.index') }}" class="btn btn-primary mb-2">Data Guru</a>
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-primary mb-2">Data Siswa</a>
            <a href="{{ route('admin.kelas.index') }}" class="btn btn-primary mb-2">Data Kelas</a>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-primary mb-2">Data Jadwal</a>
        </div>
    </div>
@endsection
