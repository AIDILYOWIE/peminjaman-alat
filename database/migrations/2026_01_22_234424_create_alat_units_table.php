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
        Schema::create('alat_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id')->constrained('alat')->cascadeOnDelete();
            $table->string('unit_code')->unique(); // Unique code for each physical item
            $table->enum('status', ['ready', 'borrowed', 'damaged', 'lost', 'maintenance'])->default('ready');
            $table->string('condition')->nullable()->default('good');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat_units');
    }
};
