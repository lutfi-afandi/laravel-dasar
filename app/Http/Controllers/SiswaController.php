<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $siswas = Siswa::with('user')
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString(); //pakai ini kalo paginated pakai search
        // dd($siswas);
        return view('siswa.index', compact(
            'siswas',
            'search'
        ));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function show($id)
    {
        // return view('siswa.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'nis'   => 'required|unique:siswas,nis',
                'kelas' => 'required',
                'foto'  => 'nullable|image|max:2048'
            ],
            [
                'email.unique'  => 'email sudah terdaftar',
                'nis.unique'    => 'nis sudah terdaftar',
                'required'  => ':attribute harus diisi'
            ]
        );

        DB::beginTransaction();

        try {

            $role = Role::where('name', 'siswa')->firstOrFail();

            $user = User::create([
                'email' => $validated['email'],
                'name'  => $validated['name'],
                'password'  => Hash::make($validated['nis']),
                'role_id'   => $role->id
            ]);

            //simpan foto jika ada
            $filePath = '';
            if ($request->hasFile('foto')) {
                $filePath = $request->file('foto')->store('siswas', 'public');
            }

            // simpan ke database

            Siswa::create([
                'user_id'   => $user->id,
                'nis'   => $validated['nis'],
                'kelas' => $validated['kelas'],
                'foto'  => $filePath
            ]);

            DB::commit();

            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus foto kalau sempat diupload tapi gagal insert data
            if (!empty($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            // Kembalikan ke form dengan pesan error dan input lama
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);

        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $siswa = Siswa::with('user')->findOrFail($id);

        $validated = $request->validate(
            [
                'name' => 'required',
                // 'email' => 'required|email|unique:users,email',
                'nis'   => 'required|unique:siswas,nis,' . $siswa->id,
                'kelas' => 'required',
                'foto'  => 'nullable|image|max:2048'
            ],
            [
                'email.unique'  => 'email sudah terdaftar',
                'nis.unique'    => 'nis sudah terdaftar',
                'required'  => ':attribute harus diisi'
            ]
        );

        DB::beginTransaction();
        try {

            // Update user (hanya name)
            $siswa->user->update([
                'name' => $validated['name'],
            ]);

            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $validated['foto'] = $request->file('foto')->store('foto_siswa', 'public');
            }

            // Update siswa
            $siswa->update([
                'nis' => $validated['nis'],
                'kelas' => $validated['kelas'],
                'foto' => $validated['foto'] ?? $siswa->foto,
            ]);

            DB::commit();

            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal update siswa: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $siswa = Siswa::with('user')->findOrFail($id);

            // Soft delete siswa dan user-nya
            $siswa->delete();
            $siswa->user->delete();

            DB::commit();

            return back()->with('success', 'Data siswa berhasil dihapus (soft delete).');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus siswa: ' . $e->getMessage()]);
        }
    }
}
