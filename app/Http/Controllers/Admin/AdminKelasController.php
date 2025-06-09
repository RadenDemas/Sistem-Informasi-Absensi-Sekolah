<?php
// app/Http/Controllers/AdminKelasController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class AdminKelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::query();

        if ($request->has('search')) {
            $query->where('kelas', 'like', '%' . $request->search . '%')
                ->orWhere('sub_kelas', 'like', '%' . $request->search . '%');
        }

        $kelas = $query->orderBy('kelas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'sub_kelas' => 'required|string',
        ]);

        Kelas::create($request->only('kelas', 'sub_kelas'));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas' => 'required|string',
            'sub_kelas' => 'required|string',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->only('kelas', 'sub_kelas'));

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Kelas::destroy($id);
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
