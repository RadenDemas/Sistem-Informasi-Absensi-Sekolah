@extends('layouts.navbarkuri')

@section('content')

<h3 class="mb-4">Absensi Guru</h3>

<form method="POST" action="{{ route('wakasek.kurikulum.absen.guru.simpan') }}" enctype="multipart/form-data">
    @csrf
    @forelse($guru as $i => $s)
        <div class="student-card">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h5 class="mb-0">{{ $s->nama }}</h5>
                    <input type="hidden" name="guru_id[]" value="{{ $s->id }}">
                </div>
                <div class="col-md-9">
                    <div class="status-radio d-flex flex-wrap gap-2">
                        @foreach(['hadir', 'sakit', 'izin', 'alpha'] as $status)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input status-input" type="radio"
                                       name="status[{{ $i }}]" id="status-{{ $s->id }}-{{ $status }}"
                                       value="{{ $status }}" data-row="{{ $i }}"
                                       {{ (isset($absensiToday[$s->id]) && $absensiToday[$s->id]->status === $status) ? 'checked' : '' }} required>
                                <label class="form-check-label" for="status-{{ $s->id }}-{{ $status }}">{{ ucfirst($status) }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="details-section mt-3" id="details-{{ $i }}" style="display: none;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="keterangan-{{$i}}" class="form-label fw-bold">Keterangan</label>
                        <input type="text" name="keterangan[{{ $i }}]" id="keterangan-{{$i}}" class="form-control"
                               value="{{ $absensiToday[$s->id]->keterangan ?? '' }}" style="width:300px;" placeholder="Isi keterangan (opsional)">
                    </div>
                    <div class="col-md-6">
                        <label for="bukti-{{$i}}" class="form-label fw-bold">Upload Bukti</label>
                        <input type="file" name="bukti_keterangan[{{ $i }}]" id="bukti-{{$i}}" class="form-control" style="width:300px;">
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-hijau-sias ">
            <i class="bi bi-check-circle-fill me-2"></i> Simpan Absensi
        </button>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allStatusInputs = document.querySelectorAll('.status-input');

        function toggleDetails(input) {
            const rowIndex = input.dataset.row;
            const detailsSection = document.getElementById(`details-${rowIndex}`);
            if (!detailsSection) return;

            if (input.checked && (input.value === 'sakit' || input.value === 'izin')) {
                detailsSection.style.display = 'block';
            } else {
                detailsSection.style.display = 'none';
            }
        }

        allStatusInputs.forEach(input => {
            toggleDetails(input);

            input.addEventListener('change', () => toggleDetails(input));
        });
    });
</script>
@endsection
