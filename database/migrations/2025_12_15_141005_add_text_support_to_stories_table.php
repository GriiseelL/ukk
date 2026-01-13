<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum type untuk include 'text'
        DB::statement("ALTER TABLE stories MODIFY COLUMN type ENUM('text', 'image', 'video') NOT NULL DEFAULT 'image'");
        
        // Tambahkan kolom untuk text story jika belum ada
        Schema::table('stories', function (Blueprint $table) {
            if (!Schema::hasColumn('stories', 'text_content')) {
                $table->text('text_content')->nullable()->after('type');
            }
            
            // Make media nullable untuk text story
            $table->string('media')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE stories MODIFY COLUMN type ENUM('image', 'video') NOT NULL DEFAULT 'image'");
        
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn(['text_content', 'background']);
        });
    }
};