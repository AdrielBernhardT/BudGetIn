<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class ResetPasswordController extends Controller
{
    public function index() {
        return view('pages.auth.forgot-password.forgot');
    }

    public function sendResetLink(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT){
            toast()->success('Reset link sent to your email');
            return redirect()->back()->with('success', 'Reset link sent to your email');
        } else {
            toast()->error('Cannot send reset link to your email');
            return redirect()->back()->withErrors(['email' => __($status)]);
        }
    }

    public function resetForm(Request $request, $token)
    {
        return view('pages.auth.forgot-password.reset', [
            'token' => $token,
            'email' => $request->email
        ]); 
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8' ,'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successful')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
