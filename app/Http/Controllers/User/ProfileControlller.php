<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileControlller extends Controller
{
    public function index() {
        return(view('pages.user.profile', ['title' => 'Profile']));
    }

    public function updateProfileInformation(Request $request) {
        $user = Auth::user();
        $profile = Profile::firstOrCreate([
            'user_id' => $user->id
        ]);

        $user -> update([
            'fname' => $request->filled('fname') ? $request->fname : $user->fname,
            'lname' => $request->filled('lname') ? $request->lname : $user->lname,
            'email' => $request->filled('email') ? $request->email : $user->email
        ]);

        $profile -> update([
            'phone' => $request->filled('phone') ? $request->phone : $profile->phone,
            'bio' => $request->filled('bio') ? $request->bio : $profile->bio
        ]);

        return redirect()->back()->with('success', 'Profile is updated successfully!');
    }

    public function updateAddressInformation(Request $request) {
        $user = Auth::user();

        $profile = Profile::firstOrCreate([
            'user_id' => $user->id
        ]);

        $profile -> update([
            'country' => $request->filled('country') ? $request->country : $profile->country,
            'postal_code' => $request->filled('postal_code') ? $request->postal_code : $profile->postal_code,
            'city' => $request->filled('city') ? $request->city : $profile->city,
        ]);

        return redirect()->back()->with('success', 'Profile is updated successfully!');
    }
}
