@extends('layouts.admin')

@section('content')
    <style>
        .form-card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            padding: 2.5rem;
        }
        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-green {
            background-color: #1E6042;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
        }
        .btn-green:hover {
            background-color: #15472F;
            color: white;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Edit Jadwal</h1>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Jadwal
            </a>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.jadwal.update', $jadwal->jadwal_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Baris untuk Hari, Jam Mulai, dan Jam Selesai -->
                    <div class="col-md-4">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-select" required>
                            <option value="" disabled>-- Pilih Hari --</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}" {{ $jadwal->hari == $hari ? 'selected' : '' }}>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Baris untuk Jam Mulai dan Jam Selesai sejajar -->
                    <div class="col-md-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required value="{{ $jadwal->jam_mulai }}">
                            </div>
                            <div class="col-md-6">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" required value="{{ $jadwal->jam_selesai }}">
                            </div>
                        </div>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="col-md-6">
                        <label for="mapel_id" class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-select" required>
                            <option value="" disabled>-- Pilih Mapel --</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->mapel_id }}" {{ $jadwal->mapel_id == $m->mapel_id ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Guru -->
                    <div class="col-md-6">
                        <label for="guru_id" class="form-label">Guru</label>
                        <select name="guru_id" id="guru_id" class="form-select" required>
                            <option value="" disabled>-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id }}" {{ $jadwal->guru_id == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tingkat Kelas -->
                    @php
                        $tingkatKelas = $kelas->unique('kelas');
                    @endphp
                    <div class="col-md-6">
                        <label for="kelas_tingkat" class="form-label">Tingkat Kelas</label>
                        <select name="kelas_tingkat" id="kelas_tingkat" class="form-select" required>
                            <option value="" disabled>-- Pilih Tingkat --</option>
                            @foreach($tingkatKelas as $tk)
                                <option value="{{ $tk->kelas }}" {{ (isset($jadwal->kelas->kelas) && $jadwal->kelas->kelas == $tk->kelas) ? 'selected' : '' }}>
                                    {{ $tk->kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sub Kelas -->
                    <div class="col-md-6">
                        <label for="sub_kelas" class="form-label">Sub Kelas</label>
                        <select name="kelas_id" id="sub_kelas" class="form-select" required>
                            @if(isset($jadwal->kelas))
                                <option value="{{ $jadwal->kelas->id }}">
                                    {{ $jadwal->kelas->kelas }} - {{ $jadwal->kelas->sub_kelas }}
                                </option>
                            @else
                                <option value="" disabled>-- Pilih Tingkat Dulu --</option>
                            @endif
                        </select>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-green">
                            <i class="bi bi-check-circle-fill me-2"></i>Update Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('kelas_tingkat').addEventListener('change', function () {
                let tingkat = this.value;
                let subKelasSelect = document.getElementById('sub_kelas');
                subKelasSelect.innerHTML = '<option value="">Memuat...</option>';

                fetch(`/admin/jadwal/sub-kelas/${tingkat}`)
                    .then(response => response.json())
                    .then(data => {
                        subKelasSelect.innerHTML = '<option value="">-- Pilih Sub Kelas --</option>';
                        data.forEach(k => {
                            subKelasSelect.innerHTML += `<option value="${k.kelas_id}">${k.kelas} - ${k.sub_kelas}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching sub kelas:', error);
                        subKelasSelect.innerHTML = '<option value="">Gagal memuat</option>';
                    });
            });
        </script>
    @endpush
@endsection
