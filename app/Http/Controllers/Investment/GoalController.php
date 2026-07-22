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
                'icon.required' => __('sentence.goal_icon_required'),
                'name.required' => __('sentence.goal_name_required'),
                'name.unique' => __('sentence.goal_name_unique'),
                'target_amount.required' => __('sentence.goal_target_amount_required'),
                'target_amount.numeric' => __('sentence.goal_target_amount_numeric'),
                'target_amount.min' => __('sentence.goal_target_amount_min'),
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

            toast()->success(__('sentence.goal_created'));

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(__('sentence.goal_create_failed'));

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$goal) {
            toast()->error(__('sentence.goal_not_found'));

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
                'icon.required' => __('sentence.goal_icon_required'),
                'name.required' => __('sentence.goal_name_required'),
                'name.unique' => __('sentence.goal_name_unique'),
                'target_amount.required' => __('sentence.goal_target_amount_required'),
                'target_amount.numeric' => __('sentence.goal_target_amount_numeric'),
                'target_amount.min' => __('sentence.goal_target_amount_min'),
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

            toast()->success(__('sentence.goal_updated'));

            return redirect()->back();

        } catch (\Throwable $th) {

            report($th);

            toast()->error(__('sentence.goal_update_failed'));

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($goal->investments()->exists()) {
            toast()->error(__('sentence.goal_has_investments'));

            return redirect()->back();
        }

        if (!$goal) {
            toast()->error(__('sentence.goal_not_found'));

            return redirect()->back();
        }

        try {

            $goal->delete();

            toast()->success(__('sentence.goal_deleted'));

            return redirect()->back();

        } catch (\Throwable $th) {

            report($th);

            toast()->error(__('sentence.goal_delete_failed'));

            return redirect()->back();
        }
    }
}
