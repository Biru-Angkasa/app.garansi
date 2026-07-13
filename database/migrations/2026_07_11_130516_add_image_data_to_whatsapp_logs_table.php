<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            // LongText untuk menyimpan Base64 gambar
            $table->longText('image_data')->nullable()->after('pesan');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->dropColumn('image_data');
        });
    }
};