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
        Schema::create('flipside_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('flipside_post_id')->constrained('flipside_posts')->onDelete('cascade');
            $table->string('type')->default('like'); // atau 'like'/'love' dll
            $table->timestamps();

            $table->unique(['user_id', 'flipside_post_id']); // optional, supaya user cuma bisa like 1x
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flipside_likes');
    }
};
