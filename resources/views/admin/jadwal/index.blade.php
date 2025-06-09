@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Data Jadwal</h3>

        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jadwal as $j)
                <tr>
                    <td>{{ $j->hari }}</td>
                    <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td>{{ $j->mapel->nama_mapel }}</td>
                    <td>{{ $j->kelas->kelas }} - {{ $j->kelas->sub_kelas }}</td>
                    <td>{{ $j->guru->nama }}</td>
                    <td>
                        <a href="{{ route('admin.jadwal.edit', $j->jadwal_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.jadwal.destroy', $j->jadwal_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $jadwal->links() }}
    </div>
@endsection
