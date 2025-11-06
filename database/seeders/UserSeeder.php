<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $roles = Role::insert([
            [
                'name'  => 'admin',
                'description'   => 'User Admin',
            ],
            [
                'name'  => 'guru',
                'description'   => 'User Guru',
            ],
            [
                'name'  => 'siswa',
                'description'   => 'User Siswa',
            ],
        ]);

        $roleAdmin = Role::where('name', 'admin')->first();
        $roleGuru = Role::where('name', 'guru')->first();
        $roleSiswa = Role::where('name', 'siswa')->first();

        // 🔹 2. Buat 1 User Admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleAdmin->id,
            'foto' => 'foto/admin-default.png',
        ]);
        // 🔹 3. Buat 5 User Guru
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => 'guru' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roleGuru->id,
                'foto' => 'foto/guru-' . $i . '.png',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => 'GURU' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'mata_pelajaran' => $faker->randomElement(['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'Bahasa Inggris']),
                'alamat' => $faker->address(),
                'foto' => $user->foto,
            ]);
        }

        // 🔹 4. Buat 20 User Siswa
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => 'siswa' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roleSiswa->id,
                'foto' => 'foto/siswa-' . $i . '.png',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => 'SISWA' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'kelas' => $faker->randomElement(['10A', '10B', '11A', '11B', '12A', '12B']),
                'alamat' => $faker->address(),
                'foto' => $user->foto,
            ]);
        }
    }
}
