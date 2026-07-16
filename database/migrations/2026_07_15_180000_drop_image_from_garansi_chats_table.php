<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('garansi_chats', 'image')) {
            Schema::table('garansi_chats', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }

    public function down(): void
    {
        Schema::table('garansi_chats', function (Blueprint $table) {
            $table->string('image')->nullable()->after('message');
        });
    }
};
