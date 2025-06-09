@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Edit Jadwal</h3>

        <form action="{{ route('admin.jadwal.update', $jadwal->jadwal_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Hari</label>
                <select name="hari" class="form-control" required>
                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                        <option value="{{ $hari }}" {{ $jadwal->hari == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" value="{{ $jadwal->jam_mulai }}" required>
            </div>

            <div class="mb-3">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control" value="{{ $jadwal->jam_selesai }}" required>
            </div>

            <div class="mb-3">
                <label>Mata Pelajaran</label>
                <input type="text" name="mapel" class="form-control" value="{{ $jadwal->mapel }}" required>
            </div>

            <div class="mb-3">
                <label>Kelas</label>
                <select name="kelas_id" class="form-control" required>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->kelas_id }}" {{ $jadwal->kelas_id == $k->kelas_id ? 'selected' : '' }}>
                            {{ $k->kelas }} - {{ $k->sub_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Guru</label>
                <select name="guru_id" class="form-control" required>
                    @foreach ($guru as $g)
                        <option value="{{ $g->guru_id }}" {{ $jadwal->guru_id == $g->guru_id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection

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
