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
                <select name="mapel_id" id="mapel_id" class="form-control" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapel as $m)
                        <option value="{{ $m->mapel_id }}" {{ $jadwal->mapel_id == $m->mapel_id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kelas</label>
                <select id="kelas" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas->groupBy('kelas') as $k => $group)
                        <option value="{{ $k }}" {{ $group->contains('kelas_id', $jadwal->kelas_id) ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Sub Kelas</label>
                <select name="kelas_id" id="sub_kelas" class="form-control" required>
                    <option value="">-- Pilih Sub Kelas --</option>
                    @foreach($kelas as $k)
                        @if($k->kelas == $jadwal->kelas->kelas)
                            <option value="{{ $k->kelas_id }}" {{ $k->kelas_id == $jadwal->kelas_id ? 'selected' : '' }}>
                                {{ $k->kelas }} - {{ $k->sub_kelas }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Guru</label>
                <select name="guru_id" id="guru_id" class="form-control" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($guru as $g)
                        <option value="{{ $g->id }}" {{ $jadwal->guru_id == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
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

        document.getElementById('mapel_id').addEventListener('change', function () {
            let mapelId = this.value;
            fetch(`/admin/jadwal/guru-by-mapel/${mapelId}`)
                .then(response => response.json())
                .then(data => {
                    let guruSelect = document.getElementById('guru_id');
                    guruSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
                    data.forEach(guru => {
                        guruSelect.innerHTML += `<option value="${guru.id}">${guru.nama}</option>`;
                    });
                });
        });
    </script>
@endsection
