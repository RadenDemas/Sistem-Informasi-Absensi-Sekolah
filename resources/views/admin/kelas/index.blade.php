@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <h1 class="h2 mb-4 fw-bold">Manajemen Data Kelas</h1>

    <div class="data-card">
        <div class="row mb-4 align-items-center">
            <div class="col-md-5">
                <form method="GET" action="{{ route('admin.kelas.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari kelas atau sub kelas..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-7 text-end mt-3 mt-md-0">
                <a href="{{ route('admin.kelas.create') }}" class="btn btn-hijau-sias"><i class="bi bi-plus-circle me-2"></i>Tambah Kelas</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Sub Kelas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelas as $k)
                        <tr>
                            <td>{{ $k->kelas }}</td>
                            <td>{{ $k->sub_kelas }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.kelas.edit', $k->kelas_id) }}" class="btn btn-sm btn-warning btn-action me-1"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('admin.kelas.destroy', $k->kelas_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus data kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger btn-action"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted p-4">Data kelas tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($kelas instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-end mt-4">
                {{ $kelas->appends(['search' => request('search')])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection