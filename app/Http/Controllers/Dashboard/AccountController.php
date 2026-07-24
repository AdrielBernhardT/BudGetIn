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
                'name.required' => 'Account name is required.',
                'name.max' => 'Account name may not exceed 100 characters.',

                'account_identifier.unique' => 'This account number is already in use.',
                'account_identifier.max' => 'Account number may not exceed 100 characters.',

                'balance.required' => 'Initial balance is required.',
                'balance.numeric' => 'Balance must be a number.',
                'balance.min' => 'Balance cannot be negative.',
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

            toast()->success('Account created successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to create account.'
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
                    'name.required' => 'Account name is required.',
                    'name.max' => 'Account name may not exceed 100 characters.',

                    'account_identifier.unique' => 'This account number is already in use.',
                    'account_identifier.max' => 'Account number may not exceed 100 characters.'
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

            toast()->success('Account updated successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to update account.'
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
                toast()->error('This account cannot be deleted because it still has transactions.');

                return redirect()->back();
            }

            $account->delete();

            toast()->success('Account deleted successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to delete account.'
            );

            return redirect()->back();
        }
    }

    private function normalizeAmount($amount): int
    {
        return (int) preg_replace('/[^0-9]/', '', $amount);
    }
}
