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
        Schema::create('blocked_users', function (Blueprint $table) {
            $table->id();
            
            // User yang melakukan blocking
            $table->foreignId('blocker_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User yang mem-block');
            
            // User yang di-block
            $table->foreignId('blocked_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('User yang di-block');
            
            // Alasan blocking (optional)
            $table->string('reason')->nullable()->comment('Alasan blocking (optional)');
            
            // Timestamps
            $table->timestamps();
            
            // Unique constraint: satu user tidak bisa block user yang sama lebih dari sekali
            $table->unique(['blocker_id', 'blocked_id'], 'unique_block_relationship');
            
            // Indexes untuk performa query
            $table->index('blocker_id', 'idx_blocker');
            $table->index('blocked_id', 'idx_blocked');
            $table->index(['blocker_id', 'blocked_id'], 'idx_block_pair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_users');
    }
};