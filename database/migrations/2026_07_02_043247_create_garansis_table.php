<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('garansis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_hp');
            $table->string('invoice_pembelian')->nullable();
            $table->date('tanggal_beli');
            $table->string('nama_marketplace');
            $table->text('kerusakan');
            $table->text('kelengkapan_barang');
            $table->string('lokasi_chat');
            $table->enum('status', [
                'pending',
                'repair',
                'replace',
                'to distribution',
                'pengiriman',
                'selesai',
            ])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_sampai')->useCurrent(); // auto timestamp saat create
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garansis');
    }
};