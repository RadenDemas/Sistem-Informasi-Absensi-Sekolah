<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Bolos;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BkController extends Controller
{
    public function index()
    {
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view('bk.dashboard', compact('tingkatKelas'));
    }
    public function bolos(Request $request){
        $tanggal = $request->input('tanggal', now()->toDateString());

        $bolos = Bolos::with(['siswa.kelas', 'jadwal.mapel'])
            ->whereDate('created_at', $tanggal)
            ->get();

        return view('bk.bolos.index', compact('bolos', 'tanggal'));
    }

    public function kelasList()
    {
        $kelasGroup = Kelas::select('kelas')->distinct()->get();
        return view('bk.kelas.index', compact('kelasGroup'));
    }

    public function listAbsensiByKelas($kelas_id)
    {
        $tanggal = Carbon::today()->toDateString();
        return $this->listByTanggal($kelas_id, $tanggal);
    }

    public function listByTanggal($kelas_id, $tanggal=null)
    {
        $tanggal = $tanggal ?? now()->toDateString();
        $kelas = Kelas::findOrFail($kelas_id);
        $siswa = $kelas->siswa()->with(['absensiSiswa' => function($q) use ($tanggal) {
            $q->whereDate('created_at', $tanggal);
        }])->get();

        return view('bk.absensi.index', compact('kelas', 'siswa', 'tanggal'));
    }

    public function edit($id)
    {
        $absensi = AbsensiSiswa::findOrFail($id);
        return view('bk.absensi.edit', compact('absensi'));
    }

    public function update(Request $request, $id)
    {
        $absensi = AbsensiSiswa::findOrFail($id);
        $absensi->update($request->only(['status', 'keterangan', 'bukti_keterangan']));
        return redirect()->back()->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function listSubkelas($kelas)
    {
        $subKelasList = Kelas::where('kelas', $kelas)->get();
        return view('bk.kelas.subkelas', compact('subKelasList', 'kelas'));
    }
    public function create($siswa_id, $tanggal)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        return view('bk.absensi.create', compact('siswa', 'tanggal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string',
            'bukti_keterangan' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'siswa_id' => 'required|exists:siswa,siswa_id',
            'tanggal' => 'required|date',
        ]);

        if ($request->hasFile('bukti_keterangan')) {
            $validated['bukti_keterangan'] = $request->file('bukti_keterangan')->store('bukti_keterangan', 'public');
        }

        AbsensiSiswa::create([
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? '',
            'bukti_keterangan' => $validated['bukti_keterangan'] ?? null,
            'siswa_id' => $validated['siswa_id'],
            'created_at' => $validated['tanggal'], // penting agar sesuai tanggal
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $search = $request->search;

        $query = Siswa::with('kelas')->orderBy('nama');

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $siswaPaginated = $query->paginate(10)->withQueryString(); // <== pagination

        $data = [];

        foreach ($siswaPaginated as $s) {
            $hadir = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'hadir')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $sakit = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'sakit')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $izin = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'izin')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $alpha = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'alpha')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            // Hitung hanya satu bolos per hari
            $bolos = Bolos::where('siswa_id', $s->siswa_id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->selectRaw('COUNT(DISTINCT DATE(created_at)) as jumlah')
                ->value('jumlah');

            $data[] = [
                'siswa' => $s,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'bolos' => $bolos,
            ];
        }

        return view('bk.rekap.index', [
            'siswaPaginated' => $siswaPaginated,
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'search' => $search
        ]);
    }

    public function downloadRekap(Request $request){
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $search = $request->search;

        $query = Siswa::with('kelas')->orderBy('nama');

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $siswaList = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Hadir');
        $sheet->setCellValue('E1', 'Sakit');
        $sheet->setCellValue('F1', 'Izin');
        $sheet->setCellValue('G1', 'Alpha');
        $sheet->setCellValue('H1', 'Bolos');

        $row = 2;

        foreach ($siswaList as $s) {
            $hadir = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'hadir')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $sakit = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'sakit')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $izin = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'izin')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $alpha = AbsensiSiswa::where('siswa_id', $s->siswa_id)
                ->where('status', 'alpha')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $bolos = Bolos::where('siswa_id', $s->siswa_id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->selectRaw('COUNT(DISTINCT DATE(created_at)) as jumlah')
                ->value('jumlah');

            $sheet->setCellValue('A' . $row, $s->nis);
            $sheet->setCellValue('B' . $row, $s->nama);
            $sheet->setCellValue('C' . $row, $s->kelas->kelas . '-' . $s->kelas->sub_kelas);
            $sheet->setCellValue('D' . $row, $hadir);
            $sheet->setCellValue('E' . $row, $sakit);
            $sheet->setCellValue('F' . $row, $izin);
            $sheet->setCellValue('G' . $row, $alpha);
            $sheet->setCellValue('H' . $row, $bolos);

            $row++;
        }

        $filename = "Rekap_Absensi_{$bulan}_{$tahun}.xlsx";
        $writer = new Xlsx($spreadsheet);

        // Simpan ke output
        $tempFile = tempnam(sys_get_temp_dir(), 'rekap');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
