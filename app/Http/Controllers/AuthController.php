<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /*
    * menampilkan halaman login admin
    */
    public function login()
    {
        return view('admin.login');
    }

    /*
    * melakukan proses autentikasi login admin
    */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'Hak akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
            }

            return redirect()->intended('dashboard');
        }

        return back()->with('error', 'Login gagal, silahkan periksa kembali email dan password Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /*
    * menampilkan halaman dashboard admin
    */
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
