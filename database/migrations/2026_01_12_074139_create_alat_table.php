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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->string('name');
            $table->text('description');
            $table->integer('stock')->comment('Berkurang otomatis via Trigger saat status = siap_diambil/dipinjam');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
