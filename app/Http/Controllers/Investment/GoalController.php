<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function store(Request $request){
        $validated = $request->validateWithBag('goal', [
            'icon' => ['required'],
            'name' => ['required', 'string', 'max:100', 'unique:goals,name,NULL,id,user_id,' . Auth::id()],
            'target_amount' => ['required'],
            'target_date' => ['nullable', 'date_format:d-m-Y'],
        ]);
        $user = Auth::user();

        Goal::create([
            'user_id'=> $user->id,
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'target_amount' => $validated['target_amount'],
            'target_date' => !empty($validated['target_date'])
                ? Carbon::createFromFormat('d-m-Y', $validated['target_date'])->format('Y-m-d')
                : null,
        ]);

        toast()->success('Goal created!');
        return redirect()->back()->with('success', 'Goal created!');
    }
}
