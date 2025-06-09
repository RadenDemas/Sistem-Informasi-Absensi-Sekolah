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
        $keyword = $request->input('search');

        $jadwal = Jadwal::with(['mapel', 'kelas', 'guru'])
            ->when($keyword, function ($query, $keyword) {
                $query->whereHas('mapel', function ($q) use ($keyword) {
                    $q->where('nama_mapel', 'like', "%{$keyword}%");
                })->orWhereHas('guru', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('hari')
            ->paginate(10);

        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $guru = User::all();
        $mapel = Mapel::all();
        $kelas = Kelas::selectRaw('DISTINCT kelas')->pluck('kelas');
        return view('admin.jadwal.create', compact('mapel', 'kelas','guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mapel_id' => 'required|exists:mapel,mapel_id',
            'kelas_id' => 'required|exists:kelas,kelas_id',
            'guru_id' => 'required|exists:users,id',
        ]);

        Jadwal::create([
            'mapel_id' => $request->mapel_id,
            'guru_id' => $request->guru_id,
            'kelas_id' => $request->kelas_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $mapel = Mapel::all();
        $kelas = Kelas::all();
        $guru = User::all();
        return view('admin.jadwal.edit', compact('jadwal', 'kelas', 'guru', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mapel_id' => 'required|exists:mapel,mapel_id',
            'kelas_id' => 'required|exists:kelas,kelas_id',
            'guru_id' => 'required|exists:users,id',
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
