<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🔥 UBAH KOLOM RFID_TAG JADI NULLABLE
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rfid_tag')->nullable()->change();
        });
    }

    /**
     * 🔄 KEMBALIKAN SEPERTI SEMULA (OPTIONAL)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rfid_tag')->nullable(false)->change();
        });
    }
};
