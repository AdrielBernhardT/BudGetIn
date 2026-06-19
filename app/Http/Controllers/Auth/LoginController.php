<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function index() {
        return view('pages.auth.login', ['title' => 'Sign In']);
    }

    public function store(Request $request){
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(Auth::attempt($attributes)){
            return redirect()->route('dashboard');
        }

        return redirect()
            ->back()
            ->with('error', 'Invalid credential or account not found')
            ->withInput();;
    }

    public function destroy(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
