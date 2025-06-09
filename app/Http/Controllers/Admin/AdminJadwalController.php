<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Mapel;

class AdminJadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwal = Jadwal::with(['kelas', 'guru'])->latest()->paginate(10);
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $mapel = Mapel::all();
        $kelas = Kelas::selectRaw('DISTINCT kelas')->pluck('kelas');
        return view('admin.jadwal.create', compact('mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mapel' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,kelas_id',
            'guru_id' => 'required|exists:guru,guru_id',
        ]);

        Jadwal::create($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelas = Kelas::all();
        $guru = User::all();
        return view('admin.jadwal.edit', compact('jadwal', 'kelas', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mapel' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,kelas_id',
            'guru_id' => 'required|exists:guru,guru_id',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Jadwal::destroy($id);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function getGuruByMapel($mapel_id)
    {
        $guru = User::where('mapel_id', $mapel_id)->get();
        return response()->json($guru);
    }

    public function getSubKelas($kelas)
    {
        $subKelas = Kelas::where('kelas', $kelas)->get();
        return response()->json($subKelas);
    }
}
