<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Friends;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ambil semua teman yang di-follow
        $friends = Friends::where('user_id', $user->id)->pluck('user_following')->toArray();

        // ambil post dari user yang di-follow + user sendiri
        $data = Posts::with('user')
            ->withCount('likes')
            ->withExists([
                'likes as is_liked_by_auth_user' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ])
            ->whereIn('user_id', $friends)   // post teman
            ->orWhere('user_id', $user->id)  // post user sendiri
            ->latest()
            ->get();


        return view('homepage', [
            'posts' => $data
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not authenticated');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        } else {
            $data['image'] = null;
        }

        $data['user_id'] = $user->id;

        Posts::create($data);

        return redirect()->route('homepage')->with('success', 'Post berhasil dibuat');
    }

    public function update(StorePostRequest $request, Posts $post)
    {
        $validatedData = $request->validated();

        $post->update([
            'caption' => $validatedData['caption'],
        ]);

        return response()->json([
            'success' => true,
            'post' => $post
        ]);
    }

    public function destroy(Posts $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'status' => 200
        ]);
    }
}