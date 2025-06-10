<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;

class AdminController extends Controller
{
    public function index(){
        $guru = User::count();
        $siswa = Siswa::count();
        $kelas = Kelas::count();
        return view('admin.dashboard', compact('guru', 'siswa', 'kelas'));
    }
}
