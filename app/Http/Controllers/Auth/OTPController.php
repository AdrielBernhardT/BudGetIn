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
        if (auth()->user()->email_verified_at) {
            return redirect()->route('dashboard');
        }

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
            'otpExpiresIn' => $remainingSeconds,
            'title' => 'Verify Account'
        ]);
    }

    public function send()
    {
        $user = auth()->user();

        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        if (!Cache::has("otp_{$user->id}")) {
            $this->generateAndSendOtp($user);
            toast()->success('OTP sent successfully');
        } else {
            toast()->info('An OTP is already active. Please check your email.');
        }

        return redirect()
            ->route('verify.index');
    }

    public function generateAndSendOtp($user): void
    {
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
        Cache::forget("otp_expire_{$user->id}");

        return redirect()->route('dashboard')
            ->with('success', 'Account verified successfully');
    }

    public function resend()
    {
        $user = auth()->user();
        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        $this->generateAndSendOtp($user);

        toast()->success('A new OTP has been sent');

        return back()->with(
            'success',
            'A new OTP has been sent'
        );
    }
}
