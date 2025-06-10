<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Bolos;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KepalaSekolahController extends Controller
{
    public function index()
    {
        $siswa = Siswa::count();
        $hadirSiswa = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'hadir')
            ->count();
        $izinSiswa = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'izin')
            ->count();
        $sakitSiswa = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'sakit')
            ->count();
        $alphaSiswa = AbsensiSiswa::whereDate('created_at', Carbon::today())
            ->where('status', 'alpha')
            ->count();
        $bolos = Bolos::whereDate('created_at', Carbon::today())->count();
        $guru = User::count();
        $hadirGuru = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'hadir')
            ->count();
        $izinGuru = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'izin')
            ->count();
        $sakitGuru = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'sakit')
            ->count();
        $alphaGuru = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'alpha')
            ->count();
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view("kepala_sekolah.dashboard",compact('siswa',
            'hadirSiswa',
            'izinSiswa',
            'sakitSiswa',
            'alphaSiswa',
            'guru',
            'hadirGuru',
            'izinGuru',
            'sakitGuru',
            'alphaGuru',
            'tingkatKelas',
            'bolos'));
    }

    public function kelasList()
    {
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view('kepala_sekolah.kelas.index', compact('tingkatKelas'));
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

        return view('kepala_sekolah.absensi_siswa.index', compact('kelas', 'siswa', 'tanggal'));
    }

    public function listSubkelas($kelas)
    {
        $subKelasList = Kelas::where('kelas', $kelas)->get();
        return view('kepala_sekolah.kelas.subkelas', compact('subKelasList', 'kelas'));
    }

    public function bolos(Request $request){
        $tanggal = $request->input('tanggal', now()->toDateString());

        $bolos = Bolos::with(['siswa.kelas', 'jadwal.mapel'])
            ->whereDate('created_at', $tanggal)
            ->get();

        return view('kepala_sekolah.bolos.index', compact('bolos', 'tanggal'));
    }

    public function monitoringGuru(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $nama = $request->input('nama');

        $data = DB::table('users')
            ->leftJoin('absensi_guru', function ($join) use ($tanggal) {
                $join->on('users.id', '=', 'absensi_guru.guru_id')
                    ->whereDate('absensi_guru.tanggal', '=', $tanggal);
            })
            ->select(
                'users.nama as nama_guru',
                'absensi_guru.status',
                'absensi_guru.keterangan',
                'absensi_guru.bukti'
            )
            ->when($nama, function ($query, $nama) {
                return $query->where('users.nama', 'LIKE', "%{$nama}%");
            })
            ->paginate(10);

        // Pastikan untuk mengoper semua data request (tanggal, nama) agar pagination melanjutkan query string
        return view('kepala_sekolah.absensi_guru.index', compact('data', 'tanggal', 'nama'));
    }
    public function rekapSiswa(Request $request)
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

        return view('kepala_sekolah.rekap.siswa', [
            'siswaPaginated' => $siswaPaginated,
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'search' => $search
        ]);
    }

    public function downloadRekapSiswa(Request $request){
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
    public function rekapGuru(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $search = $request->search;

        $query = User::orderBy('nama');

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $guruPaginated = $query->paginate(10)->withQueryString(); // <== pagination

        $data = [];

        foreach ($guruPaginated as $g) {
            $hadir = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'hadir')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $sakit = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'sakit')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $izin = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'izin')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $alpha = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'alpha')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $data[] = [
                'guru' => $g,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
            ];
        }

        return view('kepala_sekolah.rekap.guru', [
            'guruPaginated' => $guruPaginated,
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'search' => $search
        ]);
    }

    public function downloadRekapGuru(Request $request){
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $search = $request->search;

        $query = User::orderBy('nama');

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $guruList = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Hadir');
        $sheet->setCellValue('D1', 'Sakit');
        $sheet->setCellValue('E1', 'Izin');
        $sheet->setCellValue('F1', 'Alpha');

        $row = 2;

        foreach ($guruList as $g) {
            $hadir = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'hadir')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $sakit = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'sakit')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $izin = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'izin')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $alpha = AbsensiGuru::where('guru_id', $g->id)
                ->where('status', 'alpha')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $sheet->setCellValue('A' . $row, $g->nip);
            $sheet->setCellValue('B' . $row, $g->nama);
            $sheet->setCellValue('C' . $row, $hadir);
            $sheet->setCellValue('D' . $row, $sakit);
            $sheet->setCellValue('E' . $row, $izin);
            $sheet->setCellValue('F' . $row, $alpha);

            $row++;
        }

        $filename = "Rekap_Absensi_Guru_{$bulan}_{$tahun}.xlsx";
        $writer = new Xlsx($spreadsheet);

        // Simpan ke output
        $tempFile = tempnam(sys_get_temp_dir(), 'rekap');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
