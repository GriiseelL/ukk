<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('image', 'public');
        }

        $user = auth()->user(); // atau Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
                'status' => 401
            ], 401);
        }


        $posts = Posts::create([
            'user_id' => auth()->user()->id,
            'caption' => $validatedData['caption'],
            'image' => $validatedData['image'],
        ]);

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function update(StorePostRequest $request, Posts $posts)
    {
        $validatedData = $request->validated();


        unset($validatedData['image']);

        $posts->update([
            'caption' => $validatedData['caption'],
        ]);

        return response()->json([
            'success' => true,
            'product' => $posts
        ]);
    }

    public function destroy(Posts $posts)
    {

        if ($posts->image) {
            Storage::disk('public')->delete($posts->image);
        }
        $posts->delete();

        return response()->json([
            'success' => true,
            'status' => 200
        ]);
    }
}