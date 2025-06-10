@extends('layouts.admin')

@section('content')

    <style>
        .form-card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            padding: 2.5rem;
        }
        .form-control, .form-select {
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
            <h1 class="h2 fw-bold">Tambah Jadwal Baru</h1>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Jadwal
            </a>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Row Hari, Jam Mulai & Jam Selesai -->
                    <div class="col-md-4">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Hari --</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Mengelompokkan Jam Mulai & Jam Selesai dalam satu baris -->
                    <div class="col-md-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="col-md-6">
                        <label for="mapel_id" class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Mapel --</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->mapel_id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Guru -->
                    <div class="col-md-6">
                        <label for="guru_id" class="form-label">Guru</label>
                        <select name="guru_id" id="guru_id" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tingkat Kelas -->
                    <div class="col-md-6">
                        <label for="kelas_tingkat" class="form-label">Tingkat Kelas</label>
                        <!-- Pastikan memberikan attribute 'name' jika diperlukan pada backend -->
                        <select name="kelas_tingkat" id="kelas_tingkat" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Tingkat --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sub Kelas -->
                    <div class="col-md-6">
                        <label for="sub_kelas" class="form-label">Sub Kelas</label>
                        <select name="kelas_id" id="sub_kelas" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Tingkat Dulu --</option>
                        </select>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-green">
                            <i class="bi bi-check-circle-fill me-2"></i>Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('kelas_tingkat').addEventListener('change', function () {
            let kelas = this.value;
            fetch(`/admin/jadwal/sub-kelas/${kelas}`)
                .then(response => response.json())
                .then(data => {
                    let subKelasSelect = document.getElementById('sub_kelas');
                    subKelasSelect.innerHTML = '<option value="">-- Pilih Sub Kelas --</option>';
                    data.forEach(k => {
                        subKelasSelect.innerHTML += `<option value="${k.kelas_id}">${k.kelas} - ${k.sub_kelas}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error fetching sub kelas:', error);
                    document.getElementById('sub_kelas').innerHTML = '<option value="">Gagal memuat</option>';
                });
        });
    </script>
@endsection
