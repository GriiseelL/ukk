<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    // public function index() {
    //     $data = User::get();

    //     view()->share([
    //         'user' => $data
    //     ]);

    //     return view('login');
    // }

   function regis(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:50|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $data = new User();
    $data->name = $request->name;
    $data->username = $request->username;
    $data->email = $request->email;
    $data->password = Hash::make($request->password);
    $data->save();

    return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login!');
}

    function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('homepage');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}