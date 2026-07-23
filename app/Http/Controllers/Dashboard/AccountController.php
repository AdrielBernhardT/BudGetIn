<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'balance' => $this->normalizeAmount($request->balance),
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'account_identifier' => [
                    'nullable',
                    'string',
                    'max:100',
                    Rule::unique('accounts', 'account_identifier')
                        ->where(fn ($query) => $query->where('user_id', Auth::id())),
                ],
                'balance' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
            ],
            [
                'name.required' => __('sentence.account_name_required'),
                'name.max' => __('sentence.account_name_max'),

                'account_identifier.unique' => __('sentence.account_number_unique'),
                'account_identifier.max' => __('sentence.account_number_max'),

                'balance.required' => __('sentence.account_balance_required'),
                'balance.numeric' => __('sentence.account_balance_numeric'),
                'balance.min' => __('sentence.account_balance_min'),
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator, 'account')
                ->withInput();
        }

        try {
            Account::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'account_identifier' => $request->account_identifier,
                'balance' => $request->balance,
            ]);

            toast()->success(__('sentence.account_created'));

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : __('sentence.account_create_failed')
            );

            return redirect()->back()->withInput();
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $account = Account::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'string',
                        'max:100',
                    ],
                    'account_identifier' => [
                        'nullable',
                        'string',
                        'max:100',
                        Rule::unique('accounts', 'account_identifier')
                            ->where(fn ($query) => $query->where('user_id', Auth::id()))
                            ->ignore($account->id),
                    ]
                ],
                [
                    'name.required' => __('sentence.account_name_required'),
                    'name.max' => __('sentence.account_name_max'),

                    'account_identifier.unique' => __('sentence.account_number_unique'),
                    'account_identifier.max' => __('sentence.account_number_max')
                ]
            );

            if ($validator->fails()) {
                toast()->error($validator->errors()->first());

                return redirect()
                    ->back()
                    ->withErrors($validator, 'account')
                    ->withInput();
            }

            $account->update([
                'name' => $request->name,
                'account_identifier' => $request->account_identifier
            ]);

            toast()->success(__('sentence.account_updated'));

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : __('sentence.account_update_failed')
            );

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $account = Account::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if (
                $account->outgoingTransactions()->exists() ||
                $account->incomingTransactions()->exists()
            ) {
                toast()->error(__('sentence.account_has_transactions'));

                return redirect()->back();
            }

            $account->delete();

            toast()->success(__('sentence.account_deleted'));

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : __('sentence.account_delete_failed')
            );

            return redirect()->back();
        }
    }

    private function normalizeAmount($amount): int
    {
        return (int) preg_replace('/[^0-9]/', '', $amount);
    }
}
