<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::table('garansis', function (Blueprint $table) {
            $table->string('sn_pengganti')->nullable()->after('catatan');
            $table->string('resi_pengiriman')->nullable()->after('sn_pengganti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garansis', function (Blueprint $table) {
            //
        });
    }
};
