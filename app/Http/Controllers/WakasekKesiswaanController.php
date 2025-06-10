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

class WakasekKesiswaanController extends Controller
{
    public function index()
    {
        $siswa = Siswa::count();
        $hadir = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'hadir')
            ->count();
        $izin = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'izin')
            ->count();
        $sakit = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'sakit')
            ->count();
        $alpha = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'alpha')
            ->count();
        $bolos = Bolos::whereDate('created_at', Carbon::today())->count();
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view('wakasek_kesiswaan.dashboard', compact('tingkatKelas', 'siswa', 'hadir', 'izin', 'sakit', 'alpha', 'bolos'));
    }

    public function kelasList()
    {
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view('wakasek_kesiswaan.kelas.index', compact('tingkatKelas'));
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

        return view('wakasek_kesiswaan.absensi.index', compact('kelas', 'siswa', 'tanggal'));
    }

    public function listSubkelas($kelas)
    {
        $subKelasList = Kelas::where('kelas', $kelas)->get();
        return view('wakasek_kesiswaan.kelas.subkelas', compact('subKelasList', 'kelas'));
    }

    public function bolos(Request $request){
        $tanggal = $request->input('tanggal', now()->toDateString());

        $bolos = Bolos::with(['siswa.kelas', 'jadwal.mapel'])
            ->whereDate('created_at', $tanggal)
            ->get();

        return view('wakasek_kesiswaan.bolos.index', compact('bolos', 'tanggal'));
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

        return view('wakasek_kesiswaan.rekap.index', [
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
