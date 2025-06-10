@extends('layouts.navbarguru')

@section('content')
    <div class="form-container-card">
        <h3>Form Bolos</h3>
        @if (session('failed'))
            <div class="alert alert-danger">
                {{ session('failed') }}
            </div>
        @endif
        <form method="POST" action="{{ route('guru.bolos.simpan') }}">
                @csrf
                <div class="mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select id="kelas" class="form-select">
                        <option selected disabled>Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subkelas" class="form-label">Sub Kelas</label>
                    <select id="subkelas" class="form-select" name="kelas_id" required>
                        <option selected disabled>Pilih Sub Kelas</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="siswa_id" class="form-label">Nama Siswa</label>
                    <select id="siswa_id" class="form-select" name="siswa_id" required>
                        <option selected disabled>Pilih Siswa</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" name="keterangan" style="width: 100%;" placeholder="Isi keterangan">
                </div>

                <div class="d-flex justify-content-end gap-3" style="width: 100%;">
                    <a href="{{ url()->previous() }}" class="btn btn-kuning-sias">Batal</a>
                    <button type="submit" class="btn btn-hijau-sias">Simpan Perubahan</button>
                </div>
        </form>
    </div>

    <script>
        document.getElementById('kelas').addEventListener('change', function () {
            const kelas = this.value;
            fetch(`/guru/get-subkelas/${kelas}`)
                .then(res => res.json())
                .then(data => {
                    const subkelasSelect = document.getElementById('subkelas');
                    subkelasSelect.innerHTML = '<option selected disabled>Pilih Sub Kelas</option>';
                    data.forEach(sub => {
                        subkelasSelect.innerHTML += `<option value="${sub.kelas_id}">${sub.kelas} - ${sub.sub_kelas}</option>`;
                    });
                });
        });

        document.getElementById('subkelas').addEventListener('change', function () {
            const kelas_id = this.value;
            fetch(`/guru/get-siswa/${kelas_id}`)
                .then(res => res.json())
                .then(data => {
                    const siswaSelect = document.getElementById('siswa_id');
                    siswaSelect.innerHTML = '<option selected disabled>Pilih Siswa</option>';
                    data.forEach(s => {
                        siswaSelect.innerHTML += `<option value="${s.siswa_id}">${s.nama}</option>`;
                    });
                });
        });
    </script>
@endsection
