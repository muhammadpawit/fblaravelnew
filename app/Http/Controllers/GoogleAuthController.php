<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoogleCallbackRequest;
use App\Services\GoogleAccountService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return to_route('login')->withErrors(['google' => 'Login Google belum tersedia. Gunakan username dan password.']);
        }

        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function callback(GoogleCallbackRequest $request, GoogleAccountService $accounts): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return to_route('login')->withErrors(['google' => 'Login Google belum tersedia. Gunakan username dan password.']);
        }

        if ($request->filled('error')) {
            $request->session()->forget('state');

            return to_route('login')->withErrors(['google' => 'Login Google dibatalkan. Silakan coba lagi.']);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException) {
            return to_route('login')->withErrors(['google' => 'Sesi login Google kedaluwarsa. Silakan coba lagi.']);
        } catch (GuzzleException) {
            Log::warning('Google OAuth request failed.');

            return to_route('login')->withErrors(['google' => 'Google belum dapat dihubungi. Silakan coba lagi.']);
        }

        try {
            $user = $accounts->resolve($googleUser);
        } catch (ValidationException $exception) {
            return to_route('login')->withErrors($exception->errors());
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
