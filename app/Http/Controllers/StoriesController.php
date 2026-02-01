<?php

namespace App\Http\Controllers;

use App\Models\Stories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoriesController extends Controller
{

    public function create()
    {
        $user = Auth::user();

        // Ambil FOLLOWERS sebagai sumber teman dekat
        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.avatar')
            ->get();

        return view('StoriesCreate', compact('followers'));
    }


    public function index(Request $request)
    {
        $user = Auth::user();

        // ✅ Ambil following IDs dari tabel friends (sesuai struktur)
        $followingIds = DB::table('friends')
            ->where('user_id', $user->id) // User yang login
            ->pluck('user_following') // User yang di-follow
            ->toArray();

        $followingIds[] = $user->id; // Tambahkan ID sendiri

        $stories = Stories::with('user')
            ->whereIn('user_id', $followingIds) // Filter berdasarkan following + sendiri
            ->where('created_at', '>=', now()->subDay())
            ->get()
            ->filter(function ($story) use ($user) {
                // ✅ PRIVATE / ONLY_ME → hanya pemilik
                if (in_array($story->privacy, ['private', 'only_me']) && $story->user_id !== $user->id) {
                    return false;
                }

                // CLOSE FRIENDS → hanya pemilik & orang dalam daftar
                if ($story->privacy === 'close-friends') {
                    $allowed = $story->close_friends ?? [];
                    if (!in_array($user->id, $allowed) && $story->user_id !== $user->id) {
                        return false;
                    }
                }

                return true;
            });

        return view('stories', [
            'stories' => $stories
        ]);
    }

    // Di StoryController.php (atau controller yang handle stories)

    // Di StoryController.php (atau controller yang handle stories)

    public function store(Request $request)
    {
        // ✅ FIX: Validasi yang lebih flexible
        $validated = $request->validate([
            'type' => 'required|in:text,image,video',
            'privacy' => 'required|in:everyone, close-friends,private,only_me',
            'caption' => 'nullable|string|max:255',
            'text_content' => 'nullable|string|max:255',
            'background' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $story = new Stories();
            $story->user_id = Auth::id();
            $story->type = $request->type;
            $story->privacy = $request->privacy;
            $story->caption = $request->caption;
            $story->expires_at = now()->addHours(24);

            // Handle berdasarkan tipe
            if ($request->type === 'text') {
                $story->text_content = $request->text_content;
                $story->background = $request->background;
            } else {
                // ✅ PERBAIKAN: Gunakan kolom 'media' (bukan image_path/video_path)
                if ($request->hasFile('media')) {
                    $file = $request->file('media');
                    $path = $file->store('stories', 'public');

                    $story->media = $path; // ✅ Ubah dari image_path/video_path ke media
                }
            }

            $story->save();

            // ✅ LOGIKA CLOSE FRIENDS - Handle baik array maupun JSON string
            if ($request->privacy === 'close-friends') {

                $allowedFriends = [];

                // Cek apakah ada data close_friends
                if ($request->has('close_friends') && !empty($request->close_friends)) {

                    $closeFriendsData = $request->close_friends;

                    // ✅ Cek apakah sudah array atau masih string
                    if (is_string($closeFriendsData)) {
                        // Kalau string, decode dulu
                        $allowedFriends = json_decode($closeFriendsData, true);

                        // Validasi hasil decode
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('Format close friends tidak valid');
                        }
                    } else if (is_array($closeFriendsData)) {
                        // Kalau sudah array, langsung pakai
                        $allowedFriends = $closeFriendsData;
                    }

                    // Proses data
                    if (is_array($allowedFriends) && count($allowedFriends) > 0) {
                        // Ambil SEMUA followers user ini
                        $allFollowers = DB::table('friends')
                            ->where('user_following', Auth::id())
                            ->pluck('user_id')
                            ->toArray();

                        // Yang TIDAK dipilih = yang harus di-HIDE
                        $usersToHide = array_diff($allFollowers, $allowedFriends);

                        // Insert ke story_hidden_users (user yang TIDAK BOLEH lihat)
                        if (count($usersToHide) > 0) {
                            $hideData = [];
                            foreach ($usersToHide as $userId) {
                                $hideData[] = [
                                    'story_id' => $story->id,
                                    'user_id' => (int)$userId,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                            }

                            DB::table('story_hidden_users')->insert($hideData);

                            // Debug log
                            \Log::info('Story hidden from users:', [
                                'story_id' => $story->id,
                                'hidden_from' => $usersToHide,
                                'allowed_users' => $allowedFriends,
                                'total_hidden' => count($usersToHide)
                            ]);
                        }
                    } else {
                        // Kalau array kosong, hide dari SEMUA followers
                        $this->hideFromAllFollowers($story->id);
                    }
                } else {
                    // Kalau tidak ada data close_friends, hide dari SEMUA
                    $this->hideFromAllFollowers($story->id);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Story berhasil dibuat!',
                'story' => $story
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Story creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'type' => $request->type,
                    'privacy' => $request->privacy,
                    'close_friends_type' => gettype($request->close_friends ?? null),
                    'close_friends_value' => $request->close_friends ?? null
                ]
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat story: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper function untuk hide dari semua followers
    private function hideFromAllFollowers($storyId)
    {
        $allFollowers = DB::table('friends')
            ->where('user_following', Auth::id())
            ->pluck('user_id')
            ->toArray();

        if (count($allFollowers) > 0) {
            $hideData = [];
            foreach ($allFollowers as $userId) {
                $hideData[] = [
                    'story_id' => $storyId,
                    'user_id' => (int)$userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('story_hidden_users')->insert($hideData);

            \Log::info('Story hidden from all followers:', [
                'story_id' => $storyId,
                'total_hidden' => count($allFollowers)
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $storyId = $request->input('id');
            $story = Stories::find($storyId);

            if (!$story) {
                return response()->json([
                    'success' => false,
                    'message' => 'Story not found'
                ], 404);
            }

            if ($story->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak boleh menghapus story orang lain.'
                ], 403);
            }

            // ✅ Hapus file media (ubah dari 'media' property ke 'media' column)
            if ($story->media && Storage::disk('public')->exists($story->media)) {
                Storage::disk('public')->delete($story->media);
            }

            $story->delete();

            return response()->json([
                'success' => true,
                'message' => 'Story berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete story: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $viewUsername = $request->query('user');

        if (!$viewUsername) {
            return redirect()->route('homepage');
        }

        $viewUser = \App\Models\User::where('username', $viewUsername)->firstOrFail();

        $stories = Stories::with('user')
            ->where('user_id', $viewUser->id)
            ->where('created_at', '>=', now()->subDay())
            ->whereDoesntHave('hiddenUsers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->filter(function ($story) use ($user) {

                if ($story->user_id === $user->id) {
                    return true;
                }

                if (in_array($story->privacy, ['private', 'only_me'])) {
                    return false;
                }

                if (in_array($story->privacy, ['close-friends', 'close_friends'])) {

                    if (empty($story->close_friends)) {
                        return true;
                    }

                    $allowed = is_array($story->close_friends)
                        ? $story->close_friends
                        : json_decode($story->close_friends, true) ?? [];

                    return in_array($user->id, $allowed);
                }

                return true; // everyone
            });


        if ($stories->isEmpty()) {
            return redirect()->route('homepage')
                ->with('error', 'Story tidak ditemukan atau tidak punya akses');
        }

        return view('stories', compact('stories'));
    }
}
