<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileControlller extends Controller
{
    public function index() {
        return(view('pages.user.profile', ['title' => 'Profile']));
    }

    public function updateProfileInformation(Request $request) {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user -> id) -> firstOrFail();

        $user -> update([
            'fname' => $request -> fname,
            'lname' => $request -> lname,
            'email' => $request -> email
        ]);

        $profile -> update ([
            'phone' => $request->phone,
            'bio' => $request->bio
        ]);

        return redirect()->back()->with('success', 'Profile is update');
    }

    public function updateAddressInformation(Request $request) {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user -> id) -> firstOrFail();

        $profile -> update([
            'country' => $request -> fname,
            'postal_code' => $request -> lname,
            'city' => $request -> email
        ]);

        return redirect()->back()->with('success', 'Profile is update');
    }
}
