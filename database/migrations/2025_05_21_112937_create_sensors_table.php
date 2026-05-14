<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();

            // Relasi ke slot parkir
            $table->foreignId('slot_id')
                  ->constrained('parking_slots')
                  ->onDelete('cascade');

            // Jenis sensor (ultrasonic)
            $table->string('type');

            // Status terakhir dari sensor
            $table->enum('last_status', ['kosong', 'terisi'])->nullable();

            // Waktu terakhir sensor membaca data
            $table->timestamp('last_read_at')->nullable();

            // Timestamp default Laravel (WAJIB)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}