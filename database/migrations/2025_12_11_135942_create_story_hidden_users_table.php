<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryHiddenUsersTable extends Migration
{
    public function up()
    {
        Schema::create('story_hidden_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('story_id')
                  ->constrained('stories')
                  ->onDelete('cascade');

            $table->foreignId('user_id')  // user yang disembunyikan
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('story_hidden_users');
    }
}
