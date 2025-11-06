<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔹 Bersihkan cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ================================
        // 1️⃣ Buat daftar permission
        // ================================
        $permissions = [
            // User management
            'view user',
            'create user',
            'edit user',
            'delete user',

            // Guru management
            'view guru',
            'create guru',
            'edit guru',
            'delete guru',

            // Siswa management
            'view siswa',
            'create siswa',
            'edit siswa',
            'delete siswa',

            // Profil pribadi
            'view profile',
            'edit profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2️⃣ Buat roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole  = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        $adminRole->givePermissionTo(Permission::all());

        $guruRole->givePermissionTo([
            'view siswa',
            'edit siswa',
            'view profile',
            'edit profile',
        ]);

        $siswaRole->givePermissionTo([
            'view profile',
            'edit profile',
        ]);

        // 4️⃣ Assign role ke user pertama (opsional)
        $adminUser = User::first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
