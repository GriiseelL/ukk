<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\Activity;


class ForgotPasswordController extends Controller
{
    // =============================
    // FORM INPUT EMAIL
    // =============================
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    // =============================
    // KIRIM LINK RESET
    // =============================
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        // SECURITY: jangan bocorin email
        if ($user) {

            // hapus token lama
            DB::table('password_resets')
                ->where('email', $request->email)
                ->delete();

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Mail::send('email_reset_password', [
                'token' => $token
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Password');
            });
        }

        return back()->with(
            'success',
            'Jika email terdaftar, link reset akan dikirim.'
        );
    }

    // =============================
    // FORM RESET PASSWORD
    // =============================
    public function showResetForm(Request $request)
    {
        $token = $request->query('token'); // ⬅️ AMAN

        if (!$token) {
            return view('reset-expired');
        }

        $reset = DB::table('password_resets')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return view('reset-expired');
        }

        return view('reset-password', compact('token'));
    }


    // =============================
    // PROSES RESET PASSWORD
    // =============================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $reset = DB::table('password_resets')
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return view('reset-expired');
        }

        $user = User::where('email', $reset->email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        activity_log(
            'change_password',
            $user->username . ' reset password via email'
        );



        // hapus token (sekali pakai)
        DB::table('password_resets')
            ->where('email', $reset->email)
            ->delete();

        return redirect('/')->with(
            'success',
            'Password berhasil diubah.'
        );
    }
}
