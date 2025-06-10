@extends('layouts.navbarbk')

@section('content')
    <div class="container">
        <h3>Rekap Kehadiran Siswa</h3>

        <form method="GET" class="row mb-4">
            <div class="col-md-2">
                <select name="bulan" class="form-select">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="tahun" class="form-select">
                    @for ($y = date('Y') - 2; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" value="{{ $search }}" class="form-control" style="width: 80%;" placeholder="Cari nama siswa...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-kuning-sias" type="submit">Tampilkan</button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('bk.rekap.data.download', ['bulan' => $bulan, 'tahun' => $tahun, 'search' => $search]) }}" class="btn btn-hijau-sias" style="width:fit-content;">Download</a>
            </div>
        </form>

    <div class="table-responsive table-wrapper mb-2">
        <table class="table table-bordered mb-0">
            <thead class="thead-secondary">
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Bolos</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d['siswa']->nis }}</td>
                    <td>{{ $d['siswa']->nama }}</td>
                    <td>{{ $d['siswa']->kelas->kelas }} - {{ $d['siswa']->kelas->sub_kelas }}</td>
                    <td>{{ $d['hadir'] }}</td>
                    <td>{{ $d['sakit'] }}</td>
                    <td>{{ $d['izin'] }}</td>
                    <td>{{ $d['alpha'] }}</td>
                    <td>{{ $d['bolos'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

        {{ $siswaPaginated->links() }}
    </div>
@endsection
