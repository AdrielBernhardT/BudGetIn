<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $nameParts = explode(' ', $googleUser->getName(), 2);
            $fname = $nameParts[0];
            $lname = $nameParts[1] ?? '';

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'fname'     => $user->fname ?: $fname,
                    'lname'     => $user->lname ?: $lname,
                ]);
            } else {
                $user = User::updateOrCreate(
                    ['google_id' => $googleUser->getId()],
                    [
                        'name'      => $googleUser->getName(),
                        'fname'      => $fname,
                        'lname'      => $lname,
                        'email'     => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password'  => null,
                    ]
                );
            }

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('register')
                ->with('error', 'Google sign up failed. Please try again.');
        }
    }
}
