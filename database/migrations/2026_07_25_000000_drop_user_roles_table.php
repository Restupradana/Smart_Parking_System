<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('user_roles');
    }

    public function down(): void
    {
        // Recreating this table is not needed; the pivot was replaced by role_id on users.
    }
};
