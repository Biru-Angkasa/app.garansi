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
        Schema::create('garansi_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garansi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // pengirim (teknisi/admin)
            $table->string('sender_name')->nullable(); // fallback kalau dari customer/anonim
            $table->enum('sender_type', ['teknisi', 'customer'])->default('teknisi');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garansi_chats');
    }
};
