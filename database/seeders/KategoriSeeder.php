<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Kategori::insert([
            ['nama' => 'CPU', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Mouse', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Keyboard', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Monitor', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
