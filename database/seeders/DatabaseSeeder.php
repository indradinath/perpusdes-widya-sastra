<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(1000)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'nama' => 'Admin Desa', // Nama admin
            'email' => 'admin@desadauhpeken.com', // Email admin
            'password' => Hash::make('password'), // Password admin (misal: 'password')
            'role' => 'Admin', // Role sebagai Admin
            // Pastikan kolom-kolom ini null untuk admin
            'kode_anggota' => null,
            'tanggal_lahir' => null,
            'tempat_lahir' => null,
            'jenis_kelamin' => null,
            'no_hp' => null,
            'alamat' => null,
        ]);

        User::factory()->create([
            'nama' => 'Anggota Contoh',
            'email' => 'anggota@desadauhpeken.com',
            'password' => Hash::make('password'),
            'role' => 'Anggota',
            'kode_anggota' => 'ANG0001', // Sesuaikan atau biarkan UserFactory yang generate
            'tanggal_lahir' => '2000-01-01',
            'tempat_lahir' => 'Tabanan',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Contoh No. 1, Desa Dauh Peken',
        ]);
    }
}
