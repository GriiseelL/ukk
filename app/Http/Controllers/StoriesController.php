<?php

namespace App\Http\Controllers;

use App\Models\Stories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoriesController extends Controller
{

    public function create()
    {
        return view('StoriesCreate');
    }


    public function index(Request $request)
    {
        $user = Auth::user();

        // ambil semua story aktif (<= 24 jam terakhir)
        $stories = Stories::with('user')
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stories', [
            'stories' => $stories
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'media'   => 'required|file|mimes:jpg,jpeg,png,mp4|max:10240',
            'caption' => 'nullable|string|max:255',
        ]);

        // simpan file ke storage/app/public/stories
        $path = $request->file('media')->store('stories', 'public');

        // cek tipe file
        $extension = $request->file('media')->getClientOriginalExtension();
        $type = strtolower($extension) === 'mp4' ? 'video' : 'image';

        // simpan ke database
        Stories::create([
            'user_id' => Auth::id(),
            'media'   => $path,
            'type'    => $type,
            'caption' => $request->caption,
        ]);

        return redirect()->route('stories')->with('success', 'Story berhasil ditambahkan!');
    }

    public function destroy(Stories $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'Tidak boleh menghapus story orang lain.');
        }

        // hapus file
        if ($story->media && Storage::disk('public')->exists($story->media)) {
            Storage::disk('public')->delete($story->media);
        }

        $story->delete();

        return redirect()->back()->with('success', 'Story berhasil dihapus!');
    }
}
