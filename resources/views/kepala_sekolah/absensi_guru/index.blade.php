@extends('layouts.navbarkepsek')

@section('content')
    <div class="container">
        <h2>Monitoring Absensi Guru</h2>

        <form method="GET" action="{{ route('wakasek.kurikulum.monitoring.guru') }}" class="mb-2">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-2">
                        <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-2">
                        <input type="text" name="nama" id="nama" value="{{ $nama ?? '' }}" class="form-control" placeholder="Cari nama guru">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-hijau-sias">Cari</button>
                </div>
            </div>
        </form>

        <div class="table-responsive table-wrapper mb-2">
            <table class="table table-bordered">
                <thead class="thead-secondary">
                <tr>
                    <th>Nama Guru</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Bukti</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->nama_guru }}</td>
                        <td>{{ ucfirst($item->status ?? '-') }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>
                            @if ($item->bukti)
                                <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank">Lihat Bukti</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada data guru ditemukan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $data->appends(request()->all())->links() }}
        </div>
    </div>
@endsection
