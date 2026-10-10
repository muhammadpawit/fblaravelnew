<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Two\User as GoogleUser;

class GoogleAccountService
{
    public function resolve(GoogleUser $googleUser): User
    {
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        if (! is_string($googleId) || $googleId === '' || ! is_string($email)
            || ! filter_var($email, FILTER_VALIDATE_EMAIL)
            || ($googleUser->user['email_verified'] ?? false) !== true) {
            throw ValidationException::withMessages(['google' => 'Identitas Google tidak dapat diverifikasi.']);
        }

        return DB::transaction(function () use ($googleUser, $googleId, $email): User {
            $user = User::withTrashed()->where('google_id', $googleId)->lockForUpdate()->first();

            if ($user === null) {
                $user = User::withTrashed()->where('email', $email)->lockForUpdate()->first();

                if ($user === null) {
                    throw ValidationException::withMessages(['google' => 'Error 1: Email ('.$email.') tidak ditemukan di database hosting.']);
                }
                
                if ($user->trashed()) {
                    throw ValidationException::withMessages(['google' => 'Error 2: Akun Anda berstatus terhapus (trashed) di database.']);
                }
                
                if ($user->google_id !== null) {
                    throw ValidationException::withMessages(['google' => 'Error 3: Email ini sudah terikat dengan Google ID lain. Buka phpMyAdmin, cari email Anda, dan kosongkan/NULL-kan isi kolom `google_id`.']);
                }

                /** Google is authoritative for Gmail and verified Workspace addresses only. */
                if (! Str::endsWith(Str::lower($email), '@gmail.com') && empty($googleUser->user['hd'])) {
                    throw ValidationException::withMessages(['google' => 'Penautan otomatis memerlukan Gmail atau Google Workspace. Gunakan login username Anda.']);
                }
            }

            if ($user->trashed() || Str::lower($user->email) !== Str::lower($email)) {
                throw ValidationException::withMessages(['google' => 'Akun Google ini belum diizinkan. Hubungi administrator.']);
            }

            $user->forceFill([
                'google_id' => $googleId,
                'email_verified_at' => $user->email_verified_at ?? now(),
                'last_login' => now(),
                'last_activity' => now(),
            ])->save();

            return $user;
        });
    }
}
