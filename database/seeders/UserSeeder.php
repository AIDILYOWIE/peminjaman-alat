<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('password'),
                'no_induk' => '198001012005011001', // Example NIP
                'role' => 'admin',
            ]
        );

        // Petugas
        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'password' => Hash::make('password'),
                'no_induk' => '198505052010011002', // Example NIP
                'role' => 'petugas',
            ]
        );

        // 6 Peminjam (3 Students with NISN, 3 Teachers with NIP)
        $peminjams = [
            // Siswa (NISN)
            ['username' => 'siswa1', 'no_induk' => '0012345678'],
            ['username' => 'siswa2', 'no_induk' => '0023456789'],
            ['username' => 'siswa3', 'no_induk' => '0034567890'],
            // Guru (NIP)
            ['username' => 'guru_peminjam1', 'no_induk' => '199001012015011001'],
            ['username' => 'guru_peminjam2', 'no_induk' => '199205052018012002'],
            ['username' => 'guru_peminjam3', 'no_induk' => '199508082020011003'],
        ];

        foreach ($peminjams as $peminjam) {
            User::updateOrCreate(
                ['username' => $peminjam['username']],
                [
                    'password' => Hash::make('password'),
                    'no_induk' => $peminjam['no_induk'],
                    'role' => 'peminjam',
                ]
            );
        }
    }
}
