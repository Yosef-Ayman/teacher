<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Cose\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    protected array $providers = ['google'];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, $this->providers), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, $this->providers), 404);

        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::query()
            ->where('email', $socialUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'username' => Str::slug($socialUser->getEmail()) . '-' . Str::random(6),
                'email' => $socialUser->getEmail(),
                'is_blocked' => false,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }

        Auth::login($user, remember: true);

        return Redirect::route('dashboard');
    }
}
