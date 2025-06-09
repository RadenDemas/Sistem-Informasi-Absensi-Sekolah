<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminGuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['guru_pengajar', 'guru_bk', 'wakasek_kesiswaan', 'wakasek_kurikulum','admin']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $guru = $query->paginate(10); // Atau ->get() jika tidak pakai pagination

        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
//            'nip' => 'required|nip|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(User $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
//            'nip' => 'required|nip|unique:users,nip,' . $guru->id,
        ]);

        $guru->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'password' => $request->password,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(User $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
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
            $nip = $row[1];
            $password = $row[2];
            $role = $row[3];
            if ($nama && $nip && $password) {
                if(is_null($role)){
                    User::updateOrCreate(
                        ['nip' => $nip],
                        ['nama' => $nama, 'password' => Hash::make($password)],
                    );
                }
                elseif(!is_null($role)){
                    User::updateOrCreate(
                        ['nip' => $nip],
                        ['nama' => $nama, 'password' => Hash::make($password), 'role' => $role],
                    );
                }
            }
        }

        return redirect()->route('admin.guru.index')->with('success', 'Import data Guru berhasil.');
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header Kolom
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('D1', 'Role');

        // Ambil data siswa dari database
        $user = User::all();


        $row = 2;
        foreach ($user as $s) {
            $sheet->setCellValue('A' . $row, $s->nama);
            $sheet->setCellValue('B' . $row, $s->nis);
            $sheet->setCellValue('C' . $row, ucwords(str_replace('_', ' ', $s->role)) );
            $row++;
        }

        // Output file Excel ke browser
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_user.xlsx';

        // Header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
