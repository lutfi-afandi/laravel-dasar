<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuruResuorce;
use App\Models\Guru;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message'   => 'Berhasil mengambil data Guru',
            'data' =>  GuruResuorce::collection(Guru::with('user')->get()),
        ], 200);
    }

    public function getPagination()
    {
        $gurus = Guru::with('user')->paginate(2);
        return  GuruResuorce::collection($gurus);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:20|unique:gurus,nip',
            'mata_pelajaran' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
            'unique' => ':attribute sudah terdaftar.',
            'image' => 'File :attribute harus berupa gambar.',
            'max' => 'Panjang maksimal :attribute adalah :max karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Ambil role guru
            $roleGuru = Role::where('name', 'guru')->firstOrFail();

            // Buat user baru untuk guru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->nip),
                'role_id' => $roleGuru->id,
            ]);

            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('gurus', 'public');
            }

            // Buat guru
            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'mata_pelajaran' => $request->mata_pelajaran,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
            ]);

            DB::commit();

            return new GuruResuorce($guru->load('user'));
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data guru: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return new GuruResuorce($guru);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',

            'mata_pelajaran' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',

            'unique' => ':attribute sudah terdaftar.',
            'image' => 'File :attribute harus berupa gambar.',
            'max' => 'Panjang maksimal :attribute adalah :max karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();


            // Buat user baru untuk guru
            $guru->user->update([
                'name'  => $request->name,
            ]);


            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('gurus', 'public');
            }

            // Buat guru
            $guru->update([
                'mata_pelajaran' => $request->mata_pelajaran,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
            ]);

            DB::commit();

            return new GuruResuorce($guru->load('user'));
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data guru: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $guru = Guru::with('user')->findOrFail($id);

            // Soft delete guru dan user-nya
            $guru->delete();
            $guru->user->delete();

            DB::commit();

            return response()->json(
                [
                    'message'  => ' Data guru berhasil dihapus'
                ],
                201
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message'  => ' Data guru Gagal dihapus ' . $e->getMessage()
                ],
                500
            );
        }
    }
}
