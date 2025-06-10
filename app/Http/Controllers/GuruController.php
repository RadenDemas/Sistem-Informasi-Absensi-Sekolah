<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\AbsensiSiswa;
use Carbon\Carbon;
use App\Models\Bolos;
use App\Models\Jadwal;

class GuruController extends Controller
{
    public function index()
    {
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view('guru.dashboard', compact('tingkatKelas'));
    }

    public function kelasList()
    {
        $tingkatKelas = Kelas::select('kelas')->distinct()->get();
        return view('guru.kelas.index', compact('tingkatKelas'));
    }

    public function listSubkelas($tingkat)
    {
        $now = Carbon::now();
        Carbon::setLocale('id');
        $hariIni = $now->translatedFormat('l');
        $guru_id = auth()->user()->id;
        $jadwalHariIni = Jadwal::where('hari', $hariIni)
            ->where('guru_id', $guru_id)
            ->whereHas('kelas', function ($query) use ($tingkat) {
                $query->where('kelas', $tingkat);
            })
            ->orderBy('jam_mulai')
            ->get();

        $jamPertama = optional($jadwalHariIni->first())->jam_mulai;

        // Ambil semua kelas_id yang sesuai dengan jam pertama (jika ada)
        $kelasIdJamPertama = $jadwalHariIni
            ->where('jam_mulai', $jamPertama)
            ->pluck('kelas_id');

        $subkelas = Kelas::whereIn('kelas_id', $kelasIdJamPertama)->get();

        return view('guru.kelas.subkelas', compact('subkelas', 'tingkat'));
    }

    public function absenForm($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);
        $siswa = $kelas->siswa;

        $today = Carbon::today();

        $absensiToday = AbsensiSiswa::whereIn('siswa_id', $siswa->pluck('siswa_id'))
            ->whereDate('created_at', $today)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absen_form', compact('kelas', 'siswa', 'absensiToday'));
    }

    public function absenSimpan(Request $request, $kelas_id)
    {
        $siswa_id = $request->input('siswa_id', []);
        $statuses = $request->input('status', []);
        $keterangans = $request->input('keterangan', []);
        $buktis = $request->file('bukti_keterangan', []);

        $today = Carbon::today();

        foreach ($siswa_id as $index => $id) {
            $existing = AbsensiSiswa::where('siswa_id', $id)
                ->whereDate('created_at', $today)
                ->first();

            $bukti = $existing->bukti_keterangan ?? null;

            // Jika ada file bukti baru diunggah
            if (isset($buktis[$index])) {
                $bukti = $buktis[$index]->store('bukti_keterangan', 'public');
            }

            if ($existing) {
                $existing->update([
                    'status' => $statuses[$index],
                    'keterangan' => $keterangans[$index],
                    'bukti_keterangan' => $bukti,
                ]);
            } else {
                AbsensiSiswa::create([
                    'siswa_id' => $id,
                    'status' => $statuses[$index],
                    'keterangan' => $keterangans[$index],
                    'bukti_keterangan' => $bukti,
                ]);
            }
        }

        return redirect()->route('guru.dashboard')->with('success', 'Absensi berhasil disimpan.');
    }
    public function getSubkelas($kelas)
    {
        return Kelas::where('kelas', $kelas)->get();
    }

    public function getSiswa($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);
        return $kelas->siswa;
    }

    public function formBolos()
    {
        $kelas = Kelas::select('kelas')->distinct()->pluck('kelas');
        return view('guru.form_bolos', compact('kelas'));
    }

    public function simpanBolos(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,kelas_id',
            'siswa_id' => 'required|exists:siswa,siswa_id',
            'keterangan' => 'required|string|max:255',
        ]);
        $hari = Carbon::now(); // atau tanggal dari form
        Carbon::setLocale('id');
        $jam = $hari->format('H:i:s');
        $namaHari = $hari->translatedFormat('l');
        $kelas_id = $request->kelas_id;
        $absenHariIni = AbsensiSiswa::where('siswa_id', $request->siswa_id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'hadir')
            ->exists();
        if($absenHariIni){
            $jadwal = Jadwal::where('hari', $namaHari)
                ->where('kelas_id', $kelas_id)
                ->where('jam_mulai', '<=', $jam)
                ->where('jam_selesai', '>=', $jam)
                ->first();
            Bolos::create([
                'siswa_id' => $request->siswa_id,
                'keterangan' => $request->keterangan,
                'jadwal_id' => $jadwal->jadwal_id,
            ]);

            return redirect()->route('guru.dashboard')->with('success', 'Form bolos berhasil dikirim.');
        }
        else{
            return redirect()->route('guru.form.bolos')->with('failed', 'Form bolos gagal dikirim. Kemungkinan murid sedang sakit/izin/alpha atau belum diabsenkan');
        }
    }
}
