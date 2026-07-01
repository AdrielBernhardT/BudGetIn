<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index() {
        return view('pages.auth.register', ['title' => 'Sign Up']);
    }

    public function store(Request $request){
        $attributes = $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',              
                'regex:/[a-z]/',      
                'regex:/[A-Z]/',      
                'regex:/[0-9]/',      
                'regex:/[@$!%*?&#]/', 
                'confirmed'           
            ],
            'terms' => ['accepted'],
        ]);

        try {
            $user = User::create([
                'fname' => $attributes['fname'],
                'lname' => $attributes['lname'],
                'email' => $attributes['email'],
                'name' => $attributes['fname'] . ' ' . $attributes['lname'],
                'password' => Hash::make($attributes['password'])
            ]);

            Auth::login($user);
            return redirect()->route('verify.send');

        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong while creating your account')
                ->withInput();
        }


    }
}
