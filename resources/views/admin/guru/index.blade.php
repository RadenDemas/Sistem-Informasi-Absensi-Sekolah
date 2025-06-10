@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <h1 class="h2 mb-4 fw-bold">Manajemen Data Guru</h1>

    <div class="data-card">
        <div class="row mb-4 align-items-center">
            <div class="col-md-5">
                <form method="GET" action="{{ route('admin.guru.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIP guru..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-7 text-end mt-3 mt-md-0">
                <a href="{{ route('admin.guru.create') }}" class="btn btn-kuning-sias"><i class="bi bi-plus-circle me-2"></i>Tambah Guru</a>
                <a href="{{ route('admin.guru.export-excel') }}" class="btn btn-hijau-sias"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($guru as $g)
                        <tr>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->nip }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $g->role)) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-sm btn-warning btn-action me-1"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus data guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger btn-action"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Data guru tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            {{ $guru->appends(['search' => request('search')])->links() }}
        </div>
    </div>

    <div class="data-card mt-4">
        <h4 class="fw-bold">Import Data Guru dari Excel</h4>
        <p class="text-muted">Gunakan fitur ini untuk mengunggah data guru dalam jumlah besar.</p>
        <hr>
        <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">File Excel (.xlsx)</label>
                <input type="file" name="file" id="file" class="form-control" accept=".xlsx" style="width: 100%;" required>
            </div>
            <button class="btn btn-secondary"><i class="bi bi-upload me-2"></i>Import Data</button>
        </form>
    </div>
</div>
@endsection