<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();

            // user
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // media
            $table->string('media'); // path foto/video
            $table->enum('type', ['image', 'video']); // tipe media

            // caption opsional
            $table->string('caption')->nullable();

            // privacy settings
            $table->enum('privacy', ['public', 'close_friends', 'custom_hide'])
                ->default('public');

            // waktu hilang (24 jam dll)
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stories');
    }
}
