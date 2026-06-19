<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() {
        return view('pages.auth.register', ['title' => 'Sign Up']);
    }

    public function store(Request $request){
        $attributes = $request->validate([
            'fname' => ['required'],
            'lname' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        try {
            $user = User::create([
                'name' => $attributes['fname']. ' ' .$attributes['lname'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password'])
            ]);

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (\Throwable $th) {

            return redirect()
                ->back()
                ->with('error', 'Something went wrong while creating your account')
                ->withInput();
        }


    }
}
