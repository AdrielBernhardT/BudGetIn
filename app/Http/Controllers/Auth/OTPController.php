<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function index(){
        $user = auth()->user();
        $expiresAt = Cache::get("otp_expire_{$user->id}");
        $remainingSeconds = 0;

        if($expiresAt){
            $remainingSeconds = max(
                0,
                $expiresAt - now()->timestamp
            );
        }

        return view('pages.auth.verify-account', [
            'otpExpiresIn' => $remainingSeconds
        ]);
    }

    public function send()
    {
        $user = auth()->user();

        $otp = random_int(100000, 999999);

        $expiresAt = now()->addMinutes(3);
        Cache::put(
            "otp_{$user->id}",
            $otp,
            $expiresAt
        );

        Cache::put(
            "otp_expire_{$user->id}",
            $expiresAt->timestamp,
            $expiresAt
        );

        Mail::to($user->email)
            ->send(new OtpVerificationMail($otp));

        toast()->success('OTP sent successfully');
        return redirect()
            ->route('verify.index')
            ->with('success', 'OTP sent successfully');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6']
        ]);

        $user = auth()->user();

        $cachedOtp = Cache::get("otp_{$user->id}");

        if (!$cachedOtp) {
            return back()->with('error', 'OTP has expired');
        }

        if ($cachedOtp != $request->otp) {
            return back()->with('error', 'Invalid OTP');
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        Cache::forget("otp_{$user->id}");

        return redirect()->route('dashboard')
            ->with('success', 'Account verified successfully');
    }

    public function resend()
    {
        $user = auth()->user();

        $otp = random_int(100000, 999999);

        $expiresAt = now()->addMinutes(3);
        Cache::put(
            "otp_{$user->id}",
            $otp,
            $expiresAt
        );

        Cache::put(
            "otp_expire_{$user->id}",
            $expiresAt->timestamp,
            $expiresAt
        );

        Mail::to($user->email)
            ->send(new OtpVerificationMail($otp));

        toast()->success('A new OTP has been sent');
        return back()->with(
            'success',
            'A new OTP has been sent'
        );
    }
}
