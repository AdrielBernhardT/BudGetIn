<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function index() {
        return view('pages.auth.login', ['title' => __('auth.sign_in')]);
    }

    public function store(Request $request){
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $remember = $request->boolean('remember');

        if(Auth::attempt($attributes, $remember)){
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return redirect()
            ->back()
            ->with('error', __('auth.invalid_credentials'))
            ->withInput();;
    }

    public function destroy(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
