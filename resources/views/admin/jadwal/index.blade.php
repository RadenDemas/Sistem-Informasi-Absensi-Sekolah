@extends('layouts.admin')

@section('content')

    <style>
        .data-card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            padding: 2rem;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            white-space: nowrap;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .pagination .page-link {
            color: #1E6042;
        }
        .pagination .page-item.active .page-link {
            background-color: #1E6042;
            border-color: #1E6042;
        }
    </style>

    <div class="container-fluid">
        <h1 class="h2 mb-4 fw-bold">Manajemen Jadwal Pelajaran</h1>

        <div class="data-card">
            {{-- Header: Search dan Tombol Aksi --}}
            <div class="row mb-4 align-items-center">
                <div class="col-md-5">
                    <form method="GET" action="{{ route('admin.jadwal.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari mapel atau guru..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-7 text-end mt-3 mt-md-0">
                    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Jadwal</a>
                    <a href="{{ route('admin.jadwal.exportExcel') }}" class="btn btn-success"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Kelas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($jadwal as $j)
                        <tr>
                            <td>{{ $j->hari }}</td>
                            <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                            <td>{{ $j->mapel->nama_mapel ?? 'N/A' }}</td>
                            <td>{{ $j->guru->nama ?? 'N/A' }}</td>
                            <td>{{ $j->kelas->kelas ?? 'N/A' }}-{{ $j->kelas->sub_kelas ?? '' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.jadwal.edit', $j->jadwal_id) }}" class="btn btn-sm btn-warning btn-action me-1"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('admin.jadwal.destroy', $j->jadwal_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger btn-action"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">Data jadwal tidak ditemukan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if ($jadwal->hasPages())
                <div class="d-flex justify-content-end mt-4">
                    {{ $jadwal->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
