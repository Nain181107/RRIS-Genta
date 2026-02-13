<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'id_karyawan' => 'required',
            'password' => 'required',
        ], [
            'id_karyawan.required' => 'Harap isi ID Karyawan.',
            'password.required' => 'Harap isi password.',
        ]);

        $inputId = $request->id_karyawan;

        $karyawan = Karyawan::whereRaw('BINARY id_karyawan = ?', [$inputId])->first();

        if (!$karyawan || !Hash::check($request->password, $karyawan->password)) {
            return back()->withErrors([
                'login_error' => 'ID Karyawan atau Password salah.'
            ])->withInput();
        }

        Session::put([
            'karyawan_id' => $karyawan->id,
            'id_karyawan' => $karyawan->id_karyawan,
            'nama_karyawan' => $karyawan->nama_lengkap,
            'jabatan' => $karyawan->jabatan,
            'tim' => $karyawan->tim,
            'tgl_lahir' => $karyawan->tgl_lahir,
            'nohp' => $karyawan->nohp,
            'foto' => $karyawan->foto,
        ]);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login.form');
    }
}
