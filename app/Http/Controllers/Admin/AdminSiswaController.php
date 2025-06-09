<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas')->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $siswa = $query->paginate(10);

        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|unique:siswa',
            'kelas_id' => 'required|exists:kelas,kelas_id',
        ]);

        Siswa::create($request->all());
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|unique:siswa,nis,' . $id . ',siswa_id',
            'kelas_id' => 'required|exists:kelas,kelas_id',
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Siswa::destroy($id);
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach (array_slice($rows, 1) as $row) {
            $nama = $row[0];
            $nis = $row[1];
            $kelasId = $row[2]; // Pastikan ini sesuai dengan ID dari tabel kelas

            if ($nama && $nis && $kelasId) {
                Siswa::updateOrCreate(
                    ['nis' => $nis],
                    ['nama' => $nama, 'kelas_id' => $kelasId]
                );
            }
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Import data siswa berhasil.');
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header Kolom
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'Kelas');

        // Ambil data siswa dari database
        $siswa = Siswa::with('kelas')->get();

        $row = 2;
        foreach ($siswa as $s) {
            $sheet->setCellValue('A' . $row, $s->nama);
            $sheet->setCellValue('B' . $row, $s->nis);
            $sheet->setCellValue('C' . $row, $s->kelas->kelas . ' - ' . $s->kelas->sub_kelas);
            $row++;
        }

        // Output file Excel ke browser
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_siswa.xlsx';

        // Header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
