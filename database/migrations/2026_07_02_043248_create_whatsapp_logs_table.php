<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garansi_id')->nullable()->constrained()->onDelete('set null');
            $table->string('tujuan');
            $table->string('lokasi');
            $table->text('pesan');
            $table->enum('tipe', ['create', 'status_update', 'manual'])->default('manual');
            $table->enum('status_kirim', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->text('response_api')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};