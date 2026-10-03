<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Throwable;

class WamissoAuthController extends Controller
{
    public function redirect(): SymfonyRedirectResponse|RedirectResponse
    {
        if (! self::configured()) {
            return redirect()
                ->route('login')
                ->withErrors(['wamisso' => 'WamISSO login is not configured yet. Add WAMISSO_BASE_URL, WAMISSO_CLIENT_ID and WAMISSO_CLIENT_SECRET to your .env file.']);
        }

        return Socialite::driver('wamisso')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $sso = Socialite::driver('wamisso')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->withErrors(['wamisso' => 'WamISSO sign-in failed. Please try again.']);
        }

        $email = $sso->getEmail();

        if (! $email) {
            return redirect()
                ->route('login')
                ->withErrors(['wamisso' => 'WamISSO did not return an email address for this account.']);
        }

        $verified = (bool) ($sso->getRaw()['email_verified'] ?? false);

        $user = User::query()->where('wamisso_id', $sso->getId())->first();

        if (! $user) {
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                // Only link to an existing account when WamISSO vouches for the email.
                if (! $verified) {
                    return redirect()
                        ->route('login')
                        ->withErrors(['wamisso' => 'Your WamISSO email is not verified, so it cannot be linked to an existing account.']);
                }

                $user->forceFill([
                    'wamisso_id' => $sso->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            } else {
                $user = User::query()->create([
                    'name' => $sso->getName() ?: Str::before($email, '@'),
                    'email' => $email,
                    'wamisso_id' => $sso->getId(),
                    'password' => null,
                    'email_verified_at' => $verified ? now() : null,
                ]);
            }
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public static function configured(): bool
    {
        return filled(config('services.wamisso.base_url'))
            && filled(config('services.wamisso.client_id'))
            && filled(config('services.wamisso.client_secret'));
    }
}
