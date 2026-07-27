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

            confirmDelete(__('sentence.delete_transfer_confirm'));

            return view(
                'pages.transaction.transfer',
                compact('transfers', 'accounts')
            )->with('title', __('nav.transfers'));

        } catch (\Throwable $th) {
            report($th);

            toast()->error(__('sentence.failed_to_load_transfers'));

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
                'from_account_id.required' => __('sentence.select_source_account_required'),
                'from_account_id.exists' => __('sentence.select_source_account_invalid'),

                'to_account_id.required' => __('sentence.select_destination_account_required'),
                'to_account_id.exists' => __('sentence.select_destination_account_invalid'),
                'to_account_id.different' => __('sentence.accounts_must_be_different'),

                'amount.required' => __('sentence.transfer_amount_required'),
                'date.required' => __('sentence.transfer_date_required'),
                'description.max' => __('sentence.description_max'),
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
                toast()->error(__('sentence.transfer_amount_must_be_positive'));

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => __('sentence.transfer_amount_must_be_positive'),
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
                $insufficientBalanceMessage = __('sentence.insufficient_balance', [
                    'balance' => number_format($fromAccount->balance, 0, ',', '.'),
                ]);

                toast()->error($insufficientBalanceMessage);

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => $insufficientBalanceMessage,
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

            toast()->success(__('sentence.transfer_created'));

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error(__('sentence.transfer_create_failed'));

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
                'from_account_id.required' => __('sentence.select_source_account_required'),
                'from_account_id.exists' => __('sentence.select_source_account_invalid'),

                'to_account_id.required' => __('sentence.select_destination_account_required'),
                'to_account_id.exists' => __('sentence.select_destination_account_invalid'),
                'to_account_id.different' => __('sentence.accounts_must_be_different'),

                'amount.required' => __('sentence.transfer_amount_required'),
                'date.required' => __('sentence.transfer_date_required'),
                'description.max' => __('sentence.description_max'),
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
                toast()->error(__('sentence.transfer_amount_must_be_positive'));

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => __('sentence.transfer_amount_must_be_positive'),
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

                $insufficientBalanceMessage = __('sentence.insufficient_balance', [
                    'balance' => number_format($newFromAccount->balance, 0, ',', '.'),
                ]);

                toast()->error($insufficientBalanceMessage);

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => $insufficientBalanceMessage,
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

            toast()->success(__('sentence.transfer_updated'));

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error(__('sentence.transfer_update_failed'));

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

            toast()->success(__('sentence.transfer_deleted'));

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error(__('sentence.transfer_delete_failed'));

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
