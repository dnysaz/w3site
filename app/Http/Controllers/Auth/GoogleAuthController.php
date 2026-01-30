<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Mengambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Cari user berdasarkan social_id (Paling Aman)
            // 2. Jika tidak ada, cari berdasarkan email (untuk user yang sudah daftar manual sebelumnya)
            $user = User::where('social_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // UPDATE: Jika user sudah ada, kita update data social & avatar terbarunya
                $user->update([
                    'social_id'   => $googleUser->getId(),
                    'social_type' => 'google',
                    'avatar'      => $googleUser->getAvatar(),
                ]);

                Auth::login($user);
            } else {
                // REGISTER: Jika user benar-benar baru
                $newUser = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'social_id'         => $googleUser->getId(),
                    'social_type'       => 'google',
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password'          => Hash::make(Str::random(24)), 
                ]);

                Auth::login($newUser);
            }

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            
            return redirect('/login')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}