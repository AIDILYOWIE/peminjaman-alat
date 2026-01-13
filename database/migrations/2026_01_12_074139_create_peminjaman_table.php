<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('Peminjam (Siswa/Guru)');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->comment('Nullable. Diisi saat Petugas melakukan Approval');
            $table->date('tgl_pengembalian');
            $table->date('tgl_pinjam')->nullable()->comment('Diisi saat status berubah jadi DIPINJAM');
            $table->decimal('denda', 10, 2)->default(0)->comment('Dihitung jika tgl_kembali_real > tgl_kembali_rencana');
            $table->enum('status', ['pending', 'siap_diambil', 'dipinjam', 'selesai', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
