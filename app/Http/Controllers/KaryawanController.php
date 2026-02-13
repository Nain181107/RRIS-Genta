<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class KaryawanController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'id_karyawan' => 'required|unique:karyawan,id_karyawan',
            'nama_lengkap' => 'required|string|max:255',
            'tim' => 'required|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'nohp' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'password' => 'required|min:6',
            'foto' => 'nullable|file|image|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png|max:2048',
        ];

        $messages = [
            'id_karyawan.required' => 'Harap isi data terlebih dahulu.',
            'nama_lengkap.required' => 'Harap isi data terlebih dahulu.',
            'tim.required' => 'Harap isi data terlebih dahulu.',
            'nohp.required' => 'Harap isi data terlebih dahulu.',
            'jabatan.required' => 'Harap isi data terlebih dahulu.',
            'password.required' => 'Harap isi data terlebih dahulu.',

            'id_karyawan.unique' => 'ID Karyawan ini sudah terdaftar, silakan pakai ID lain.',
            'tgl_lahir.date' => 'Tanggal lahir harus format tanggal yang benar.',
            'password.min' => 'Password minimal 6 karakter.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto.mimetypes' => 'File yang diunggah bukan gambar valid.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];

        $validated = $request->validate($rules, $messages);

        $validated['tim'] = strtoupper($validated['tim']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $mime = $file->getMimeType();
            if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                return back()->withErrors(['foto' => 'File harus berupa JPG atau PNG.']);
            }

            $validated['foto'] = $file->store("karyawan/{$validated['id_karyawan']}", 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        Karyawan::create($validated);

        return redirect()->route('registrasi')->with('success', 'Registrasi Karyawan Berhasil.');
    }

    public function updateprofil(Request $request)
    {
        $karyawan = Karyawan::find(Session::get('karyawan_id'));

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'tim' => 'required|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'nohp' => 'required|string|max:20',
            'foto' => 'nullable|file|image|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png|max:2048',
        ];

        if (Session::get('jabatan') === 'Admin') {
            $rules['jabatan'] = 'required|string|max:100';
        }

        $messages = [
            'nama_lengkap.required' => 'Harap isi data terlebih dahulu.',
            'tim.required' => 'Harap isi data terlebih dahulu.',
            'nohp.required' => 'Harap isi data terlebih dahulu.',
            'tgl_lahir.date' => 'Tanggal lahir harus format tanggal yang benar.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto.mimetypes' => 'File foto bukan gambar valid.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];

        $data = $request->validate($rules, $messages);

        $data['tim'] = strtoupper($data['tim']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $mime = $file->getMimeType();
            if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                return back()->withErrors(['foto' => 'File harus berupa JPG atau PNG.']);
            }

            // Hapus foto lama jika ada
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            $data['foto'] = $file->store("karyawan/{$karyawan->id_karyawan}", 'public');
            Session::put('foto', $data['foto']);
        }

        $karyawan->update($data);

        Session::put('nama_karyawan', $data['nama_lengkap']);
        Session::put('tim', $data['tim']);
        Session::put('tgl_lahir', $data['tgl_lahir']);
        Session::put('nohp', $data['nohp']);

        if (isset($data['jabatan'])) {
            Session::put('jabatan', $data['jabatan']);
        }

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
