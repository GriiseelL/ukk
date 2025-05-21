<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function regis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        //create user
        // $otp = rand(10000, 99999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            // 'otp' => $otp,
            // 'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Mail::to($request->email)->send(new sendMail($otp));


        if ($user) {
            return response()->json([
                'message' => 'successfully',
                'success' => true,
                'user' => $user,
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }

}