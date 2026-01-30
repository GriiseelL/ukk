<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mediaable_id');
            $table->string('mediaable_type');

            $table->string('file_path');
            $table->timestamps();

            $table->index(['mediaable_id', 'mediaable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
