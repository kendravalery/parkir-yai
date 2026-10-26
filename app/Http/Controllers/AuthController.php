<?php

namespace App\Http\Controllers;

// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ],
            ['email.required' => 'Email tidak boleh kosong', 'password.required' => 'password tidak boleh kosong'],
        );

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {

                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'petugas') {

                return redirect()->route('petugas.dashboard');
            } elseif (Auth::user()->role === 'owner') {

                return redirect()->route('owner.dashboard');
            }

            Auth::logout();
            return redirect('/login')->withErrors(['role' => 'role tidak di kenali'])->withInput();
        }
        return back()->withErrors(['pesan' => 'email atau password salah'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
