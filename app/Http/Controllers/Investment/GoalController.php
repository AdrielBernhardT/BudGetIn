<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'icon' => ['required'],
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:goals,name,NULL,id,user_id,' . Auth::id()
                ],
                'target_amount' => ['required', 'numeric', 'min:1'],
                'target_date' => ['nullable'],
            ],
            [
                'icon.required' => 'Please select an icon.',
                'name.required' => 'Goal name is required.',
                'name.unique' => 'You already have a goal with this name.',
                'target_amount.required' => 'Target amount is required.',
                'target_amount.numeric' => 'Target amount must be a number.',
                'target_amount.min' => 'Target amount must be greater than 0.',
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator, 'goal')
                ->withInput();
        }

        try {
            $user = Auth::user();

            Goal::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'icon' => $request->icon,
                'target_amount' => $request->target_amount,
                'target_date' => !empty($request->target_date)
                    ? $request->target_date
                    : null,
            ]);

            toast()->success('Goal created successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error('Failed to create goal.');

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$goal) {
            toast()->error('Goal not found.');

            return redirect()->back();
        }

        $validator = Validator::make(
            $request->all(),
            [
                'icon' => ['required'],
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:goals,name,' . $goal->id . ',id,user_id,' . Auth::id(),
                ],
                'target_amount' => ['required', 'numeric', 'min:1'],
                'target_date' => ['nullable'],
            ],
            [
                'icon.required' => 'Please select an icon.',
                'name.required' => 'Goal name is required.',
                'name.unique' => 'You already have a goal with this name.',
                'target_amount.required' => 'Target amount is required.',
                'target_amount.numeric' => 'Target amount must be a number.',
                'target_amount.min' => 'Target amount must be greater than 0.',
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator, 'goal')
                ->withInput();
        }

        try {

            $goal->update([
                'icon' => $request->icon,
                'name' => $request->name,
                'target_amount' => $request->target_amount,
                'target_date' => !empty($request->target_date)
                    ? $request->target_date
                    : null,
            ]);

            toast()->success('Goal updated successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {

            report($th);

            toast()->error('Failed to update goal.');

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($goal->investments()->exists()) {
            toast()->error(
                'This goal cannot be deleted because it already contains investment data.'
            );

            return redirect()->back();
        }

        if (!$goal) {
            toast()->error('Goal not found.');

            return redirect()->back();
        }

        try {

            $goal->delete();

            toast()->success('Goal deleted successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {

            report($th);

            toast()->error('Failed to delete goal.');

            return redirect()->back();
        }
    }
}