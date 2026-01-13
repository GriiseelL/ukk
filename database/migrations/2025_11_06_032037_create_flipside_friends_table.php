<?php
/**
 * Migration untuk table flipside_friends
 * 
 * File: database/migrations/2025_01_07_000001_create_flipside_friends_table.php
 * 
 * CARA BUAT:
 * php artisan make:migration create_flipside_friends_table
 * 
 * Lalu copy kode di bawah ini ke file migration yang dibuat
 */

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
        Schema::create('flipside_friends', function (Blueprint $table) {
            $table->id();
            
            // user_id = Pemilik flipside (yang punya akun flipside)
            $table->unsignedBigInteger('user_id');
            
            // friend_id = Teman yang bisa lihat flipside (yang dikasih akses)
            $table->unsignedBigInteger('friend_id');
            
            $table->timestamps();

            // Foreign keys - hubungkan ke table users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Kalau user dihapus, data ini ikut terhapus
            
            $table->foreign('friend_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Unique constraint - Tidak bisa add teman yang sama 2 kali
            $table->unique(['user_id', 'friend_id'], 'flipside_friends_unique');
            
            // Index untuk query lebih cepat
            $table->index('user_id', 'flipside_friends_user_id_index');
            $table->index('friend_id', 'flipside_friends_friend_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flipside_friends');
    }
};

/**
 * PENJELASAN STRUKTUR:
 * 
 * Contoh data:
 * +----+---------+-----------+---------------------+
 * | id | user_id | friend_id | created_at          |
 * +----+---------+-----------+---------------------+
 * | 1  | 5       | 10        | 2025-01-07 10:00:00 |
 * | 2  | 5       | 15        | 2025-01-07 10:05:00 |
 * | 3  | 10      | 5         | 2025-01-07 10:10:00 |
 * +----+---------+-----------+---------------------+
 * 
 * Artinya:
 * - Row 1: User ID 5 mengizinkan User ID 10 melihat flipside-nya
 * - Row 2: User ID 5 mengizinkan User ID 15 melihat flipside-nya
 * - Row 3: User ID 10 mengizinkan User ID 5 melihat flipside-nya
 * 
 * CARA QUERY:
 * 
 * 1. Siapa saja yang bisa lihat flipside saya?
 *    SELECT * FROM flipside_friends WHERE user_id = 5;
 *    Hasil: User 10 dan User 15 bisa lihat flipside User 5
 * 
 * 2. Flipside siapa saja yang bisa saya lihat?
 *    SELECT * FROM flipside_friends WHERE friend_id = 5;
 *    Hasil: User 5 bisa lihat flipside User 10
 * 
 * 3. Apakah User 10 bisa lihat flipside User 5?
 *    SELECT * FROM flipside_friends 
 *    WHERE user_id = 5 AND friend_id = 10;
 *    Hasil: Ada data = bisa lihat
 */