<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function index()
    {
        try {
            $incomes = Transaction::with('toAccount')
                ->where('user_id', Auth::id())
                ->where('type', 'income')
                ->get();

            $accounts = Account::where('user_id', Auth::id())->get();

            confirmDelete('Are you sure you want to delete this income?');

            return view(
                'pages.transaction.income',
                ['title' => 'Income'],
                compact('incomes', 'accounts')
            );
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
                'title' => ['required'],
                'to_account_id' => ['required', 'exists:accounts,id'],
                'amount' => ['required'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ],
            [
                'title.required' => 'Income title is required.',
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

            $amount = (int) preg_replace('/[^0-9]/', '', $request->amount);

            $account = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $account->increment('balance', $amount);

            Transaction::create([
                'user_id'       => Auth::id(),
                'type'          => 'income',
                'title'         => $request->title,
                'amount'        => $amount,
                'to_account_id' => $account->id,
                'date'          => Carbon::createFromFormat('d-m-Y', $request->date)
                                    ->format('Y-m-d'),
                'description'   => $request->description,
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
        try {
            $income = Transaction::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('type', 'income')
                ->firstOrFail();

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => ['required'],
                    'to_account_id' => ['required', 'exists:accounts,id'],
                    'amount' => ['required'],
                    'date' => ['required'],
                    'description' => ['nullable', 'string', 'max:200'],
                ],
                [
                    'title.required' => 'Income title is required.',
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

            DB::beginTransaction();

            $oldAccount = Account::where('id', $income->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $oldAccount->decrement('balance', $income->amount);

            $newAmount = (int) preg_replace('/[^0-9]/', '', $request->amount);

            $newAccount = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newAccount->increment('balance', $newAmount);

            $income->update([
                'title'         => $request->title,
                'amount'        => $newAmount,
                'to_account_id' => $newAccount->id,
                'date'          => Carbon::createFromFormat('d-m-Y', $request->date)
                                    ->format('Y-m-d'),
                'description'   => $request->description,
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

    public function destroy($id){
        $incomes = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'income')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            Account::where('id', $incomes->to_account_id)
                ->increment('balance', $incomes->amount);

            $incomes->delete();

            DB::commit();

            toast()->success('Income deleted!');
            return redirect()->back();

        } catch(\Throwable $e){
            DB::rollBack();

            toast()->error('Delete failed!');
            return redirect()->back();
        }
    }

}
