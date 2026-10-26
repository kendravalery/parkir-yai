<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kendaraan_id')->constrained('kendaraans')->cascadeOnDelete();

            $table->string('kode_tiket', 50)->unique()->index();

            $table->dateTime('waktu_masuk')->index();
            $table->dateTime('waktu_keluar')->nullable()->index();

            $table->foreignId('tarif_id')->constrained('tarifs');

            $table->integer('durasi_menit')->nullable();
            $table->decimal('biaya_total', 13, 0)->nullable();

            $table
                ->enum('status', ['masuk', 'keluar'])
                ->default('masuk')
                ->index();

            // petugas
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('area_parkir_id')->constrained('area_parkirs')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
