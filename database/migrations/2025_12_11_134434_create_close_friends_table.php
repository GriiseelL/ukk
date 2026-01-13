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
        Schema::create('close_friend_lists', function (Blueprint $table) {
            $table->id();

            // user yang punya daftar close friends
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Pemilik daftar close friends');

            // user yang dimasukkan ke daftar close friend
            $table->foreignId('friend_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Teman yang menjadi close friend');

            $table->timestamps();

            // agar tidak bisa duplicate (user_id + friend_id)
            $table->unique(['user_id', 'friend_id'], 'unique_close_friend');

            // index untuk performa query
            $table->index('user_id', 'idx_cf_user');
            $table->index('friend_id', 'idx_cf_friend');
            $table->index(['user_id', 'friend_id'], 'idx_cf_pair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('close_friend_lists');
    }
};
