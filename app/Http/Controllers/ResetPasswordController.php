<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function sendLinkResetEmail(Request $request)
    {
        $email = $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($email);

        if ($request->expectsJson()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => __($status)
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __($status)
                ], 422);
            }
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ],[
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->back()->with('success', 'Password berhasil direset. Silahkan login dengan password baru Anda.')
            : back()->with(['error' => 'Token telah kadaluarsa. silahkan coba lagi.']);
    }
}
