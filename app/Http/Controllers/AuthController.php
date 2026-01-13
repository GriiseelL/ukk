<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;



class AuthController extends Controller
{

    // public function index() {
    //     $data = User::get();

    //     view()->share([
    //         'user' => $data
    //     ]);

    //     return view('login');
    // }



    public function regis(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // cari user by email
        $user = User::where('email', $request->email)->first();

        // ===============================
        // KASUS 1: EMAIL SUDAH ADA
        // ===============================
        if ($user) {

            // kalau SUDAH verifikasi
            if ($user->hasVerifiedEmail()) {
                return back()->withErrors([
                    'email' => 'Email sudah terdaftar. Silakan login.'
                ]);
            }

            // kalau BELUM verifikasi → kirim ulang
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')
                ->with('success', 'Email sudah terdaftar tapi belum diverifikasi. Kami kirim ulang link verifikasi.');
        }

        // ===============================
        // KASUS 2: EMAIL BARU
        // ===============================
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Akun berhasil dibuat. Cek email untuk verifikasi.');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan'
            ]);
        }

        // === CEK PASSWORD ===
        if (! \Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah'
            ]);
        }

        // === CEK EMAIL BELUM VERIFIKASI ===
        if (! $user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Email belum diverifikasi. Silakan cek email kamu.'
            ]);
        }

        // === AUTO UNSUSPEND ===
        if (
            $user->is_suspended &&
            $user->suspended_until &&
            now()->greaterThan($user->suspended_until)
        ) {
            $user->update([
                'is_suspended' => 0,
                'suspended_until' => null,
                'suspended_at' => null,
                'suspend_reason' => null,
            ]);
        }

        // === CEK MASIH SUSPENDED ===
        if ($user->is_suspended) {
            return back()->withErrors([
                'email' => 'Akun anda masih disuspend sampai: ' . $user->suspended_until
            ]);
        }

        // === LOGIN ===
        Auth::login($user);

        return redirect()->route('homepage');
    }



    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
