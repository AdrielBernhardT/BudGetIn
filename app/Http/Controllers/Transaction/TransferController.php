<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function index()
    {
        try {
            $transfers = Transaction::with(['fromAccount', 'toAccount'])
                ->where('user_id', Auth::id())
                ->where('type', 'transfer')
                ->get();

            $accounts = Account::where('user_id', Auth::id())->get();

            confirmDelete('Are you sure you want to delete this transfer?');

            return view(
                'pages.transaction.transfer',
                ['title' => 'Transfer'],
                compact('transfers', 'accounts')
            );

        } catch (\Throwable $th) {
            report($th);

            toast()->error('Failed to load transfers.');

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from_account_id' => ['required', 'exists:accounts,id'],
                'to_account_id'   => [
                    'required',
                    'different:from_account_id',
                    'exists:accounts,id'
                ],
                'amount' => ['required'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ],
            [
                'from_account_id.required' => 'Please select the source account.',
                'from_account_id.exists' => 'Selected source account is invalid.',

                'to_account_id.required' => 'Please select the destination account.',
                'to_account_id.exists' => 'Selected destination account is invalid.',
                'to_account_id.different' => 'Source and destination accounts must be different.',

                'amount.required' => 'Transfer amount is required.',
                'date.required' => 'Transfer date is required.',
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

            $fromAccount = Account::where('id', $request->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $toAccount = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $fromAccount->decrement('balance', $amount);
            $toAccount->increment('balance', $amount);

            Transaction::create([
                'user_id'         => Auth::id(),
                'type'            => 'transfer',
                'title'           => 'Transfer',
                'amount'          => $amount,
                'from_account_id' => $fromAccount->id,
                'to_account_id'   => $toAccount->id,
                'date'            => Carbon::createFromFormat('d-m-Y', $request->date)
                                        ->format('Y-m-d'),
                'description'     => $request->description,
            ]);

            DB::commit();

            toast()->success('Transfer created successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Failed to create transfer.');

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transfer = Transaction::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('type', 'transfer')
                ->firstOrFail();

            $validator = Validator::make(
                $request->all(),
                [
                    'from_account_id' => ['required', 'exists:accounts,id'],
                    'to_account_id' => [
                        'required',
                        'different:from_account_id',
                        'exists:accounts,id'
                    ],
                    'amount' => ['required'],
                    'date' => ['required'],
                    'description' => ['nullable', 'string', 'max:200'],
                ],
                [
                    'from_account_id.required' => 'Please select the source account.',
                    'from_account_id.exists' => 'Selected source account is invalid.',

                    'to_account_id.required' => 'Please select the destination account.',
                    'to_account_id.exists' => 'Selected destination account is invalid.',
                    'to_account_id.different' => 'Source and destination accounts must be different.',

                    'amount.required' => 'Transfer amount is required.',
                    'date.required' => 'Transfer date is required.',
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

            $oldFromAccount = Account::where('id', $transfer->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $oldToAccount = Account::where('id', $transfer->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $oldFromAccount->increment('balance', $transfer->amount);
            $oldToAccount->decrement('balance', $transfer->amount);

            $newAmount = (int) preg_replace('/[^0-9]/', '', $request->amount);

            $newFromAccount = Account::where('id', $request->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newToAccount = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newFromAccount->decrement('balance', $newAmount);
            $newToAccount->increment('balance', $newAmount);

            $transfer->update([
                'title' => 'Transfer',
                'amount' => $newAmount,
                'from_account_id' => $newFromAccount->id,
                'to_account_id' => $newToAccount->id,
                'date' => Carbon::createFromFormat('d-m-Y', $request->date)
                    ->format('Y-m-d'),
                'description' => $request->description,
            ]);

            DB::commit();

            toast()->success('Transfer updated successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Failed to update transfer.');

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $transfer = Transaction::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('type', 'transfer')
                ->firstOrFail();

            DB::beginTransaction();

            $fromAccount = Account::where('id', $transfer->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $toAccount = Account::where('id', $transfer->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $fromAccount->increment('balance', $transfer->amount);
            $toAccount->decrement('balance', $transfer->amount);

            $transfer->delete();

            DB::commit();

            toast()->success('Transfer deleted successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Failed to delete transfer.');

            return redirect()->back();
        }
    }
}
