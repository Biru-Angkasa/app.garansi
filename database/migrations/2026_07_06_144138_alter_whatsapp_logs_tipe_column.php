<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            // Ubah dari enum menjadi string agar fleksibel
            $table->string('tipe', 50)->default('manual')->change();
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->enum('tipe', ['create', 'status_update', 'manual'])->default('manual')->change();
        });
    }
};