<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AbsensiGuru;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class WakasekKurikulumController extends Controller
{
    public function dashboard()
    {
        $guru = User::count();
        $hadir = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'hadir')
            ->count();
        $izin = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'izin')
            ->count();
        $sakit = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'sakit')
            ->count();
        $alpha = AbsensiGuru::whereDate('created_at', Carbon::today())
            ->where('status', 'alpha')
            ->count();
        return view('wakasek_kurikulum.dashboard',compact('guru','hadir','izin','sakit','alpha'));
    }

    public function absenGuruForm()
    {
        $guru = User::all();
        $absensiToday = AbsensiGuru::select('guru_id', 'status', 'created_at')
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->unique('guru_id')
            ->keyBy('guru_id');
        return view('wakasek_kurikulum.absensi_guru', compact('guru','absensiToday'));
    }

    public function simpanAbsenGuru(Request $request)
    {
        $tanggal = Carbon::today()->toDateString();
        foreach ($request->guru_id as $index => $id) {
            AbsensiGuru::updateOrCreate(
                ['guru_id' => $id, 'tanggal' => $tanggal],
                [
                    'status' => $request->status[$index],
                    'keterangan' => $request->keterangan[$index] ?? null,
                    'bukti' => isset($request->bukti[$index]) ? $request->bukti[$index]->store('bukti_guru', 'public') : null,
                ]
            );
        }

        return redirect()->route('wakasek.kurikulum.dashboard')->with('success', 'Absensi guru berhasil disimpan.');
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
        return view('wakasek_kurikulum.monitoring_guru', compact('data', 'tanggal', 'nama'));
    }

    public function rekap(Request $request)
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

        return view('wakasek_kurikulum.rekap.index', [
            'guruPaginated' => $guruPaginated,
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
