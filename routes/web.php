<?php

use App\Http\Controllers\BkController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminGuruController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminSiswaController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\WakasekKesiswaanController;
use App\Http\Controllers\WakasekKurikulumController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    return match ($user->role) {
        'guru_pengajar' => redirect()->route('guru.dashboard'),
        'guru_bk' => redirect()->route('bk.dashboard'),
        'wakasek_kesiswaan' => redirect()->route('wakasek.kesiswaan.dashboard'),
        'wakasek_kurikulum' => redirect()->route('wakasek.kurikulum.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        'kepala_sekolah'=>redirect()->route('kepala.sekolah.dashboard'),
        default => abort(403, 'Role tidak dikenali.'),
    };
});

Route::get('/forgot-password',[ForgotPasswordController::class,'index'])->name('forgot-password');
Route::post('/forgot-password/save',[ForgotPasswordController::class,'savePassword'])->name('forgot-password.save');

Route::middleware(['auth', 'role:guru_pengajar'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
    Route::get('/guru/kelas', [GuruController::class, 'kelasList'])->name('guru.kelas');
    Route::get('/guru/kelas/{tingkat}', [GuruController::class, 'listSubkelas'])->name('guru.kelas.listSub');
    Route::get('/guru/absensi/{kelas_id}', [GuruController::class, 'absenForm'])->name('guru.absen.form');
    Route::post('/guru/absensi/{kelas_id}', [GuruController::class, 'absenSimpan'])->name('guru.absen.simpan');
    Route::get('/guru/form-bolos', [GuruController::class, 'formBolos'])->name('guru.form.bolos');
    Route::post('/guru/form-bolos', [GuruController::class, 'simpanBolos'])->name('guru.bolos.simpan');
    Route::get('/guru/get-subkelas/{kelas}', [GuruController::class, 'getSubkelas']);
    Route::get('/guru/get-siswa/{kelas_id}', [GuruController::class, 'getSiswa']);
});

Route::middleware(['auth', 'role:guru_bk'])->group(function () {
    Route::get('/bk/dashboard', [BkController::class, 'index'])->name('bk.dashboard');
    Route::get('/bk/kelas', [BkController::class, 'kelasList'])->name('bk.kelas');
    Route::get('/bk/kelas/{kelas}/sub', [BkController::class, 'listSubKelas'])->name('bk.kelas.listSub');
    Route::get('bk/absensi-kelas/{kelas_id}', [BkController::class, 'listAbsensiByKelas'])->name('bk.kelas.detail');
    Route::get('/bk/absensi-kelas/{kelas_id}/tanggal/{tanggal}', [BkController::class, 'listByTanggal'])->name('bk.kelas.tanggal');
    Route::get('/bk/absensi/create/{siswa_id}/{tanggal}', [BkController::class, 'create'])->name('bk.absensi.create');
    Route::post('/bk/absensi/store', [BkController::class, 'store'])->name('bk.absensi.store');
    Route::get('/bk/absensi/{id}/edit', [BkController::class, 'edit'])->name('bk.absensi.edit');
    Route::put('/bk/absensi/{id}', [BkController::class, 'update'])->name('bk.absensi.update');
    Route::get('/bk/monitoring-bolos', [BkController::class,'bolos'])->name('bk.bolos.index');
    Route::get('/bk/rekap-data-siswa',[BkController::class,'rekap'])->name('bk.rekap.data');
    Route::get('/bk/rekap-data-siswa/download',[BkController::class,'downloadRekap'])->name('bk.rekap.data.download');
});

Route::middleware(['auth', 'role:wakasek_kesiswaan'])->group(function () {
    Route::get('/wakasek-kesiswaan/dashboard', [WakasekKesiswaanController::class, 'index'])->name('wakasek.kesiswaan.dashboard');
    Route::get('/wakasek-kesiswaan/kelas', [WakasekKesiswaanController::class, 'kelasList'])->name('wakasek.kesiswaan.kelas');
    Route::get('/wakasek-kesiswaan/kelas/{kelas}/sub', [WakasekKesiswaanController::class, 'listSubKelas'])->name('wakasek.kesiswaan.kelas.listSub');
    Route::get('/wakasek-kesiswaan/absensi-kelas/{kelas_id}', [WakasekKesiswaanController::class, 'listAbsensiByKelas'])->name('wakasek.kesiswaan.kelas.detail');
    Route::get('/wakasek-kesiswaan/absensi-kelas/{kelas_id}/tanggal/{tanggal}', [WakasekKesiswaanController::class, 'listByTanggal'])->name('wakasek.kesiswaan.kelas.tanggal');
    Route::get('/wakasek-kesiswaan/monitoring-bolos', [WakasekKesiswaanController::class,'bolos'])->name('wakasek.kesiswaan.bolos.index');
    Route::get('/wakasek-kesiswaan/rekap-data-siswa',[WakasekKesiswaanController::class,'rekap'])->name('wakasek.kesiswaan.rekap.data');
    Route::get('/wakasek-kesiswaan/rekap-data-siswa/download',[WakasekKesiswaanController::class,'downloadRekap'])->name('wakasek.kesiswaan.rekap.data.download');
});

Route::middleware(['auth', 'role:wakasek_kurikulum'])->group(function () {
    Route::get('/wakasek-kurikulum/dashboard', [WakasekKurikulumController::class, 'dashboard'])->name('wakasek.kurikulum.dashboard');
    Route::get('/wakasek-kurikulum/absensi-guru', [WakasekKurikulumController::class, 'absenGuruForm'])->name('wakasek.kurikulum.absen.guru');
    Route::post('/wakasek-kurikulum/absensi-guru', [WakasekKurikulumController::class, 'simpanAbsenGuru'])->name('wakasek.kurikulum.absen.guru.simpan');
    Route::get('/wakasek-kurikulum/monitoring-guru', [WakasekKurikulumController::class, 'monitoringGuru'])->name('wakasek.kurikulum.monitoring.guru');
    Route::get('/wakasek-kurikulum/rekap-absensi-guru', [WakasekKurikulumController::class, 'rekap'])->name('wakasek.kurikulum.rekap.data');
    Route::get('/wakasek-kurikulum/rekap-absensi-guru/download', [WakasekKurikulumController::class, 'downloadRekap'])->name('wakasek.kurikulum.rekap.data.download');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/guru', AdminGuruController::class)->names('admin.guru')->except('show');
    Route::resource('/admin/kelas', AdminKelasController::class)->names('admin.kelas')->except('show');
    Route::resource('/admin/siswa', AdminSiswaController::class)->names('admin.siswa')->except('show');
    Route::resource('admin/jadwal', AdminJadwalController::class)->names('admin.jadwal')->except('show');
    Route::get('/admin/jadwal/guru-by-mapel/{mapel_id}', [AdminJadwalController::class, 'getGuruByMapel']);
    Route::get('/admin/jadwal/sub-kelas/{kelas}', [AdminJadwalController::class, 'getSubKelas']);
    Route::post('/admin/siswa/import', [AdminSiswaController::class, 'import'])->name('admin.siswa.import');
    Route::get('/admin/siswa/export-excel', [AdminSiswaController::class, 'exportExcel'])->name('admin.siswa.export-excel');
    Route::post('/admin/guru/import', [AdminGuruController::class, 'import'])->name('admin.guru.import');
    Route::get('/admin/guru/export-excel', [AdminGuruController::class, 'exportExcel'])->name('admin.guru.export-excel');
    Route::get('/admin/jadwal/export-excel', [AdminJadwalController::class, 'exportExcel'])->name('admin.jadwal.exportExcel');
});

Route::middleware(['auth', 'role:kepala_sekolah'])->group(function () {
    Route::get('/kepala-sekolah/dashboard',[KepalaSekolahController::class, 'index'])->name('kepala.sekolah.dashboard');
    Route::get('/kepala-sekolah/kelas', [KepalaSekolahController::class, 'kelasList'])->name('kepala.sekolah.kelas');
    Route::get('/kepala-sekolah/kelas/{kelas}/sub', [KepalaSekolahController::class, 'listSubKelas'])->name('kepala.sekolah.kelas.listSub');
    Route::get('/kepala-sekolah/absensi-kelas/{kelas_id}', [KepalaSekolahController::class, 'listAbsensiByKelas'])->name('kepala.sekolah.kelas.detail');
    Route::get('/kepala-sekolah/absensi-kelas/{kelas_id}/tanggal/{tanggal}', [KepalaSekolahController::class, 'listByTanggal'])->name('kepala.sekolah.kelas.tanggal');
    Route::get('/kepala-sekolah/monitoring-bolos', [KepalaSekolahController::class,'bolos'])->name('kepala.sekolah.bolos.index');
    Route::get('/kepala-sekolah/rekap-data-siswa',[KepalaSekolahController::class,'rekapSiswa'])->name('kepala.sekolah.rekap.data.siswa');
    Route::get('/kepala-sekolah/rekap-data-siswa/download',[KepalaSekolahController::class,'downloadRekapSiswa'])->name('kepala.sekolah.rekap.data.siswa.download');
    Route::get('/kepala-sekolah/monitoring-guru', [KepalaSekolahController::class, 'monitoringGuru'])->name('kepala.sekolah.monitoring.guru');
    Route::get('/kepala-sekolah/rekap-absensi-guru', [KepalaSekolahController::class, 'rekapGuru'])->name('kepala.sekolah.rekap.data.guru');
    Route::get('/kepala-sekolah/rekap-absensi-guru/download', [KepalaSekolahController::class, 'downloadRekapGuru'])->name('kepala.sekolah.rekap.data.guru.download');
});

Route::get('/test-auth', function () {
    return auth()->user();
});
