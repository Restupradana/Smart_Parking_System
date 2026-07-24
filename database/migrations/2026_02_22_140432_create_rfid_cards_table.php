<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_cards', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // UID RFID unik
            $table->string('uid')->unique();

            // Status kartu (aktif/nonaktif)
            $table->boolean('is_active')->default(true);

            // Tanggal daftar kartu
            $table->timestamp('registered_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_cards');
    }
};
