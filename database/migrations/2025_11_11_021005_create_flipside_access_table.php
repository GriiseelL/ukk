<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flipside_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('has_access')->default(false);
            $table->timestamps();
            
            // Pastikan kombinasi owner_id dan user_id unik
            $table->unique(['owner_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('flipside_access');
    }
};