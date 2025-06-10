@extends('layouts.navbarkuri')

@section('content')
    <div class="container">
        <h3>Rekap Kehadiran Guru</h3>

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
                <input type="text" name="search" value="{{ $search }}" class="form-control" style="width: 80%;" placeholder="Cari nama guru...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-kuning-sias" type="submit">Tampilkan</button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('wakasek.kurikulum.rekap.data.download', ['bulan' => $bulan, 'tahun' => $tahun, 'search' => $search]) }}" class="btn btn-hijau-sias">Download</a>
            </div>
        </form>

        <div class="table-responsive table-wrapper mb-2">
            <table class="table table-bordered mb-0">
                <thead class="thead-secondary">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpha</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $d['guru']->nip }}</td>
                        <td>{{ $d['guru']->nama }}</td>
                        <td>{{ $d['hadir'] }}</td>
                        <td>{{ $d['sakit'] }}</td>
                        <td>{{ $d['izin'] }}</td>
                        <td>{{ $d['alpha'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $guruPaginated->links() }}
    </div>
@endsection
