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

class TransferController extends Controller
{
    public function index()
    {
        try {
            $transfers = Transaction::with(['fromAccount', 'toAccount'])
                ->where('user_id', Auth::id())
                ->where('type', 'transfer')
                ->latest()
                ->get()->map(function ($transfer) {
                    $transfer->display_date = Carbon::parse($transfer->date)->format('d F Y');

                    return $transfer;
                });

            $accounts = Account::where('user_id', Auth::id())->get();

            confirmDelete('Are you sure you want to delete this transfer?');

            return view(
                'pages.transaction.transfer',
                compact('transfers', 'accounts')
            )->with('title', 'Transfer');

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
                'from_account_id' => [
                    'required',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
                ],
                'to_account_id' => [
                    'required',
                    'different:from_account_id',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
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
                ->withErrors($validator, 'transfer')
                ->withInput();
        }

        try {
            $amount = $this->normalizeAmount($request->amount);

            if ($amount <= 0) {
                toast()->error('Transfer amount must be greater than 0.');

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => 'Transfer amount must be greater than 0.',
                    ], 'transfer')
                    ->withInput();
            }

            $fromAccount = Account::where('id', $request->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $toAccount = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($amount > $fromAccount->balance) {
                toast()->error(
                    'Insufficient balance. Current balance: ' .
                    number_format($fromAccount->balance, 0, ',', '.')
                );

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' =>
                            'Insufficient balance. Current balance: ' .
                            number_format($fromAccount->balance, 0, ',', '.'),
                    ], 'transfer')
                    ->withInput();
            }

            DB::beginTransaction();

            $fromAccount->decrement('balance', $amount);
            $toAccount->increment('balance', $amount);

            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'transfer',
                'title' => 'Transfer',
                'amount' => $amount,
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'date' => $this->normalizeDate($request->date),
                'description' => $request->description,
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
        $transfer = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'transfer')
            ->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'from_account_id' => [
                    'required',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
                ],
                'to_account_id' => [
                    'required',
                    'different:from_account_id',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
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
                ->withErrors($validator, 'transfer')
                ->withInput();
        }

        try {
            $newAmount = $this->normalizeAmount($request->amount);

            if ($newAmount <= 0) {
                toast()->error('Transfer amount must be greater than 0.');

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => 'Transfer amount must be greater than 0.',
                    ], 'transfer')
                    ->withInput();
            }

            $oldFromAccount = Account::where('id', $transfer->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $oldToAccount = Account::where('id', $transfer->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newFromAccount = Account::where('id', $request->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newToAccount = Account::where('id', $request->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            DB::beginTransaction();

            // Balikin efek transfer lama
            $oldFromAccount->increment('balance', $transfer->amount);
            $oldToAccount->decrement('balance', $transfer->amount);

            // Refresh supaya saldo terbaru kebaca setelah rollback transfer lama
            $newFromAccount->refresh();

            if ($newAmount > $newFromAccount->balance) {
                DB::rollBack();

                toast()->error(
                    'Insufficient balance. Current balance: ' .
                    number_format($newFromAccount->balance, 0, ',', '.')
                );

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' =>
                            'Insufficient balance. Current balance: ' .
                            number_format($newFromAccount->balance, 0, ',', '.'),
                    ], 'transfer')
                    ->withInput();
            }

            // Terapkan transfer baru
            $newFromAccount->decrement('balance', $newAmount);
            $newToAccount->increment('balance', $newAmount);

            $transfer->update([
                'title' => 'Transfer',
                'amount' => $newAmount,
                'from_account_id' => $newFromAccount->id,
                'to_account_id' => $newToAccount->id,
                'date' => $this->normalizeDate($request->date),
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
        $transfer = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'transfer')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $fromAccount = Account::where('id', $transfer->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $toAccount = Account::where('id', $transfer->to_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Balikin efek transfer
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