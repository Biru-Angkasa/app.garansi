<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('garansi_items', function (Blueprint $table) {
            // SN lama (kolom `serial_number`) dibiarkan apa adanya sebagai histori.
            // SN baru diisi saat barang di-replace, tanpa menimpa data SN lama.
            $table->string('serial_number_baru')->nullable()->after('serial_number');
            $table->timestamp('replaced_at')->nullable()->after('serial_number_baru');

            // Index buat mempercepat pencarian berdasarkan SN.
            $table->index('serial_number');
            $table->index('serial_number_baru');
        });
    }

    public function down(): void
    {
        Schema::table('garansi_items', function (Blueprint $table) {
            $table->dropIndex(['serial_number']);
            $table->dropIndex(['serial_number_baru']);
            $table->dropColumn(['serial_number_baru', 'replaced_at']);
        });
    }
};