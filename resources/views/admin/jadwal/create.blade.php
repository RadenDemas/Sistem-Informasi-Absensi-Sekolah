@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Tambah Jadwal</h3>

        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Hari</label>
                <select name="hari" class="form-control" required>
                    <option value="">-- Pilih Hari --</option>
                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                        <option value="{{ $hari }}">{{ $hari }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="form-control" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapel as $m)
                        <option value="{{ $m->mapel_id }}">{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Guru</label>
                <select name="guru_id" id="guru_id" class="form-control" required>
                    <option value="">-- Pilih Guru --</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Kelas</label>
                <select name="kelas" id="kelas" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Sub Kelas</label>
                <select name="kelas_id" id="sub_kelas" class="form-control" required>
                    <option value="">-- Pilih Sub Kelas --</option>
                </select>
            </div>

            <button class="btn btn-success">Simpan</button>
        </form>
    </div>
    <script>
        document.getElementById('mapel_id').addEventListener('change', function () {
            let mapelId = this.value;
            fetch(`/admin/jadwal/guru-by-mapel/${mapelId}`)
                .then(response => response.json())
                .then(data => {
                    let guruSelect = document.getElementById('guru_id');
                    guruSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
                    data.forEach(guru => {
                        guruSelect.innerHTML += `<option value="${guru.guru_id}">${guru.nama}</option>`;
                    });
                });
        });

        document.getElementById('kelas').addEventListener('change', function () {
            let kelas = this.value;
            fetch(`/admin/jadwal/sub-kelas/${kelas}`)
                .then(response => response.json())
                .then(data => {
                    let subKelasSelect = document.getElementById('sub_kelas');
                    subKelasSelect.innerHTML = '<option value="">-- Pilih Sub Kelas --</option>';
                    data.forEach(k => {
                        subKelasSelect.innerHTML += `<option value="${k.kelas_id}">${k.kelas} - ${k.sub_kelas}</option>`;
                    });
                });
        });
    </script>
@endsection

