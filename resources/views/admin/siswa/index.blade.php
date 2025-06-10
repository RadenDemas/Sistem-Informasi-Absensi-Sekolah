@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <h1 class="h2 mb-4 fw-bold">Manajemen Data Siswa</h1>

    <div class="data-card">
        <div class="row mb-4 align-items-center">
            <div class="col-md-5">
                <form method="GET" action="{{ route('admin.siswa.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIS siswa..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-7 text-end mt-3 mt-md-0">
                <a href="{{ route('admin.siswa.create') }}" class="btn btn-kuning-sias"><i class="bi bi-plus-circle me-2"></i>Tambah Siswa</a>
                <a href="{{ route('admin.siswa.export-excel') }}" class="btn btn-hijau-sias"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</a>
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
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa as $s)
                        <tr>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->nis }}</td>
                            <td>{{ $s->kelas->kelas ?? 'N/A' }}-{{ $s->kelas->sub_kelas ?? '' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.siswa.edit', $s->siswa_id) }}" class="btn btn-sm btn-warning btn-action me-1"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('admin.siswa.destroy', $s->siswa_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger btn-action"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Data siswa tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            {{ $siswa->appends(['search' => request('search')])->links() }}
        </div>

    </div>

    <div class="data-card mt-4">
        <h4 class="fw-bold">Import Data Siswa dari Excel</h4>
        <p class="text-muted">Gunakan fitur ini untuk mengunggah data siswa dalam jumlah besar.</p>
        <hr>
        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
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