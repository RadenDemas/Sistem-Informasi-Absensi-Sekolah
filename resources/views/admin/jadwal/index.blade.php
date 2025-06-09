@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Data Jadwal</h3>

        <form action="{{ route('admin.jadwal.index') }}" method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari mapel atau nama guru" value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mapel</th>
                <th>Guru</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jadwal as $j)
                <tr>
                    <td>{{ $j->hari }}</td>
                    <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td>{{ $j->mapel->nama_mapel }}</td>
                    <td>{{ $j->guru->nama }}</td>
                    <td>{{ $j->kelas->kelas }} - {{ $j->kelas->sub_kelas }}</td>
                    <td>
                        <a href="{{ route('admin.jadwal.edit', $j->jadwal_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.jadwal.destroy', $j->jadwal_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus jadwal ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $jadwal->withQueryString()->links() }}
    </div>
@endsection
