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
        Schema::create('rfid_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke user dan kartu RFID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rfid_card_id')->constrained()->onDelete('cascade');

            // Tambahkan slot_id agar bisa ambil lokasi
            $table->foreignId('slot_id')->nullable()->constrained('parking_slots')->onDelete('set null');

            $table->enum('action', ['masuk', 'keluar', 'ditolak']);

            // Kolom location tetap ada untuk menyimpan nama area
            $table->string('location')->nullable();

            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_logs');
    }
};