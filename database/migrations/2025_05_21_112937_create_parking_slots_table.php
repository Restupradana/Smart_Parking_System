<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('parking_areas')->onDelete('cascade');
            $table->string('slot_number');
            $table->enum('status', ['kosong', 'terisi'])->default('kosong');
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('parking_slots');
    }
};
