<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function store(Request $request){
        $validated = $request->validateWithBag('goal', [
            'icon' => ['required'],
            'name' => ['required', 'string', 'max:100', 'unique:goals,name,NULL,id,user_id,' . Auth::id()],
            'target_amount' => ['required']
        ]);
        $user = Auth::user();

        Goal::create([
            'user_id'=> $user->id,
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'target_amount' => $validated['target_amount']
        ]);

        toast()->success('Goal created!');
        return redirect()->back()->with('success', 'Goal created!');
    }
}
