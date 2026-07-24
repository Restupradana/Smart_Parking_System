<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rfid_logs', function ($table) {
            $table->index('logged_at');
            $table->index('user_id');
        });

        Schema::table('parking_slots', function ($table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('rfid_logs', function ($table) {
            $table->dropIndex(['logged_at']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('parking_slots', function ($table) {
            $table->dropIndex(['status']);
        });
    }
};
