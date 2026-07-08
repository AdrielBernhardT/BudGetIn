<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IncomeController extends Controller
{
    public function index()
    {
        try {
            $incomes = Transaction::with('toAccount')
                ->where('user_id', Auth::id())
                ->where('type', 'income')
                ->latest()
                ->get();

            $accounts = Account::where('user_id', Auth::id())->get();

            confirmDelete('Are you sure you want to delete this income?');

            return view(
                'pages.transaction.income',
                compact('incomes', 'accounts')
            )->with('title', 'Income');

        } catch (\Throwable $th) {
            report($th);

            toast()->error('Failed to load incomes.');

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => ['required', 'string', 'max:100'],
                'to_account_id' => [
                    'required',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
                ],
                'amount' => ['required'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ],
            [
                'title.required' => 'Income title is required.',
                'title.max' => 'Income title may not exceed 100 characters.',
                'to_account_id.required' => 'Please select an account.',
                'to_account_id.exists' => 'Selected account is invalid.',
                'amount.required' => 'Amount is required.',
                'date.required' => 'Date is required.',
                'description.max' => 'Description may not exceed 200 characters.',
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $amount = $this->normalizeAmount($request->amount);

            if ($amount <= 0) {
                toast()->error('Amount must be greater than 0.');

                return redirect()
                    ->back()
                    ->withInput();
            }

            $account = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $account->increment('balance', $amount);

            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'income',
                'title' => $request->title,
                'amount' => $amount,
                'to_account_id' => $account->id,
                'date' => $this->normalizeDate($request->date),
                'description' => $request->description,
            ]);

            DB::commit();

            toast()->success('Income created successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Failed to create income.');

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $income = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'income')
            ->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'title' => ['required', 'string', 'max:100'],
                'to_account_id' => [
                    'required',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
                ],
                'amount' => ['required'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ],
            [
                'title.required' => 'Income title is required.',
                'title.max' => 'Income title may not exceed 100 characters.',
                'to_account_id.required' => 'Please select an account.',
                'to_account_id.exists' => 'Selected account is invalid.',
                'amount.required' => 'Amount is required.',
                'date.required' => 'Date is required.',
                'description.max' => 'Description may not exceed 200 characters.',
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldAccount = Account::where('id', $income->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $oldAccount->decrement('balance', $income->amount);

            $newAmount = $this->normalizeAmount($request->amount);

            if ($newAmount <= 0) {
                DB::rollBack();

                toast()->error('Amount must be greater than 0.');

                return redirect()
                    ->back()
                    ->withInput();
            }

            $newAccount = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newAccount->increment('balance', $newAmount);

            $income->update([
                'title' => $request->title,
                'amount' => $newAmount,
                'to_account_id' => $newAccount->id,
                'date' => $this->normalizeDate($request->date),
                'description' => $request->description,
            ]);

            DB::commit();

            toast()->success('Income updated successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Failed to update income.');

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $income = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'income')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            Account::where('id', $income->to_account_id)
                ->where('user_id', Auth::id())
                ->decrement('balance', $income->amount);

            $income->delete();

            DB::commit();

            toast()->success('Income deleted!');

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Delete failed!');

            return redirect()->back();
        }
    }

    private function normalizeAmount($amount): int
    {
        return (int) preg_replace('/[^0-9]/', '', $amount);
    }

    private function normalizeDate($date): string
    {
        try {
            return Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        } catch (\Throwable $th) {
            return Carbon::parse($date)->format('Y-m-d');
        }
    }
}