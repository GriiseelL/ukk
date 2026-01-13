<?php
/**
 * Migration untuk table flipside_posts
 * 
 * File: database/migrations/2025_01_07_000002_create_flipside_posts_table.php
 * 
 * CARA BUAT:
 * php artisan make:migration create_flipside_posts_table
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
        Schema::create('flipside_posts', function (Blueprint $table) {
            $table->id();
            
            // user_id = Pemilik postingan
            $table->unsignedBigInteger('user_id');
            
            // caption = Text postingan
            $table->text('caption')->nullable();
            
            // image_front = Gambar sisi depan (yang keliatan pertama kali)
            $table->string('image_front');
            
            // image_back = Gambar sisi belakang (yang keliatan setelah di-flip)
            $table->string('image_back');
            
            // likes_count = Jumlah likes
            $table->unsignedInteger('likes_count')->default(0);
            
            // views_count = Jumlah views
            $table->unsignedInteger('views_count')->default(0);
            
            // is_flipside = True jika ini postingan flipside (private), false jika public
            $table->boolean('is_flipside')->default(false);
            
            // status = active/archived/deleted
            $table->enum('status', ['active', 'archived', 'deleted'])->default('active');
            
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete

            // Foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indexes untuk query lebih cepat
            $table->index('user_id', 'flipside_posts_user_id_index');
            $table->index('is_flipside', 'flipside_posts_is_flipside_index');
            $table->index('status', 'flipside_posts_status_index');
            $table->index('created_at', 'flipside_posts_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flipside_posts');
    }
};

/**
 * PENJELASAN STRUKTUR:
 * 
 * Contoh data:
 * +----+---------+----------+---------------+--------------+-------------+-------------+-------------+--------+---------------------+
 * | id | user_id | caption  | image_front   | image_back   | likes_count | views_count | is_flipside | status | created_at          |
 * +----+---------+----------+---------------+--------------+-------------+-------------+-------------+--------+---------------------+
 * | 1  | 5       | Beach 🏖️ | front_1.jpg   | back_1.jpg   | 245         | 1230        | false       | active | 2025-01-07 10:00:00 |
 * | 2  | 5       | Secret   | front_2.jpg   | back_2.jpg   | 89          | 456         | true        | active | 2025-01-07 11:00:00 |
 * | 3  | 10      | Summer   | front_3.jpg   | back_3.jpg   | 567         | 3421        | false       | active | 2025-01-07 12:00:00 |
 * +----+---------+----------+---------------+--------------+-------------+-------------+-------------+--------+---------------------+
 * 
 * Artinya:
 * - Post ID 1: Postingan PUBLIC User 5, beach theme dengan 245 likes
 * - Post ID 2: Postingan FLIPSIDE (private) User 5, hanya teman yang bisa lihat
 * - Post ID 3: Postingan PUBLIC User 10, summer theme dengan 567 likes
 * 
 * CARA QUERY:
 * 
 * 1. Ambil semua postingan PUBLIC User 5:
 *    SELECT * FROM flipside_posts 
 *    WHERE user_id = 5 AND is_flipside = false AND status = 'active';
 * 
 * 2. Ambil semua postingan FLIPSIDE User 5:
 *    SELECT * FROM flipside_posts 
 *    WHERE user_id = 5 AND is_flipside = true AND status = 'active';
 * 
 * 3. Apakah User 10 bisa lihat Post ID 2 milik User 5?
 *    - Cek dulu apakah Post 2 adalah flipside: is_flipside = true
 *    - Kalau true, cek table flipside_friends:
 *      SELECT * FROM flipside_friends 
 *      WHERE user_id = 5 AND friend_id = 10;
 *    - Kalau ada data = User 10 bisa lihat
 *    - Kalau tidak ada = User 10 TIDAK bisa lihat
 * 
 * 4. Feed Home (postingan PUBLIC dari semua user):
 *    SELECT * FROM flipside_posts 
 *    WHERE is_flipside = false AND status = 'active'
 *    ORDER BY created_at DESC;
 * 
 * 5. Feed Flipside (postingan PRIVATE dari teman-teman User 10):
 *    SELECT p.* FROM flipside_posts p
 *    INNER JOIN flipside_friends f ON p.user_id = f.user_id
 *    WHERE f.friend_id = 10 
 *    AND p.is_flipside = true 
 *    AND p.status = 'active'
 *    ORDER BY p.created_at DESC;
 * 
 * FIELD EXPLANATION:
 * 
 * - image_front: Path ke gambar depan (contoh: storage/posts/front_abc123.jpg)
 * - image_back: Path ke gambar belakang (contoh: storage/posts/back_abc123.jpg)
 * - is_flipside: 
 *   * false = Postingan PUBLIC (semua orang bisa lihat)
 *   * true = Postingan FLIPSIDE (hanya close friends yang bisa lihat)
 * - status:
 *   * active = Postingan aktif, bisa dilihat
 *   * archived = Postingan diarsip user, tidak muncul di feed
 *   * deleted = Postingan dihapus soft (masih di database tapi tidak ditampilkan)
 * - softDeletes: Ketika dihapus, tidak benar-benar hilang dari database
 */