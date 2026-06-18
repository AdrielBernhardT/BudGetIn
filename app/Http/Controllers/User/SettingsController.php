<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index() {
        confirmDelete('Are you sure want to delete this account?');
        return(view('pages.user.settings', ['title' => 'Settings']));
    }

    public function changePassword(Request $request){
        $request->validate([
            'currentPassword' => ['required'],
            'newPassword' => ['required', 'different:currentPassword'],
            'confirmPassword' => ['required', 'same:newPassword']
        ]);
        
        $user = Auth::user();

        if(!Hash::check($request->currentPassword, $user->password)){
            return back()->withInput()->with('error', 'Current password is incorrect!');
        }

        $user->update([
            'password' => Hash::make($request->newPassword)
        ]);

        return redirect()->back()->with('success', 'Password is updated!');
    }

    public function deleteAccount(Request $request){
        // ini nanti kasi minta password biar ga langsung delete
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('login');

    }
}
