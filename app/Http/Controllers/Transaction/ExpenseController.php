<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index()
    {
        try {
            $expenses = Transaction::with(['fromAccount', 'category'])
                ->where('user_id', Auth::id())
                ->where('type', 'expense')
                ->latest()
                ->get()
                ->map(function ($expense) {
                    $expense->display_date = Carbon::parse($expense->date)->format('d F Y');

                    return $expense;
                });

            $categories = Category::where('user_id', Auth::id())->get();

            $accounts = Account::where('user_id', Auth::id())->get();

            confirmDelete(__('sentence.delete_expense_confirm'));

            return view(
                'pages.transaction.expense',
                compact('expenses', 'categories', 'accounts')
            )->with('title', __('nav.expenses'));

        } catch (\Throwable $th) {
            report($th);

            toast()->error(__('sentence.failed_to_load_expenses'));

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => ['required', 'string', 'max:100'],
                'from_account_id' => [
                    'required',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
                ],
                'category_id' => [
                    'required',
                    Rule::exists('categories', 'id')->where('user_id', Auth::id()),
                ],
                'amount' => ['required'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ],
            [
                'title.required' => __('sentence.expense_title_required'),
                'title.max' => __('sentence.expense_title_max'),
                'from_account_id.required' => __('sentence.select_account_required'),
                'from_account_id.exists' => __('sentence.select_account_invalid'),
                'category_id.required' => __('sentence.select_category_required'),
                'category_id.exists' => __('sentence.select_category_invalid'),
                'amount.required' => __('sentence.amount_required'),
                'date.required' => __('sentence.date_required'),
                'description.max' => __('sentence.description_max'),
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator, 'expense')
                ->withInput();
        }

        try {
            $amount = $this->normalizeAmount($request->amount);

            if ($amount <= 0) {
                toast()->error(__('sentence.amount_must_be_positive'));

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => __('sentence.amount_must_be_positive'),
                    ], 'expense')
                    ->withInput();
            }

            $account = Account::where('id', $request->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($amount > $account->balance) {
                $insufficientBalanceMessage = __('sentence.insufficient_balance', [
                    'balance' => number_format($account->balance, 0, ',', '.'),
                ]);

                toast()->error($insufficientBalanceMessage);

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => $insufficientBalanceMessage,
                    ], 'expense')
                    ->withInput();
            }

            $category = Category::where('id', $request->category_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            DB::beginTransaction();

            $account->decrement('balance', $amount);

            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'expense',
                'title' => $request->title,
                'amount' => $amount,
                'from_account_id' => $account->id,
                'category_id' => $category->id,
                'date' => $this->normalizeDate($request->date),
                'description' => $request->description,
            ]);

            DB::commit();

            toast()->success(__('sentence.expense_created'));

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error(__('sentence.expense_create_failed'));

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $expense = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'expense')
            ->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'title' => ['required', 'string', 'max:100'],
                'from_account_id' => [
                    'required',
                    Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
                ],
                'category_id' => [
                    'required',
                    Rule::exists('categories', 'id')->where('user_id', Auth::id()),
                ],
                'amount' => ['required'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ],
            [
                'title.required' => __('sentence.expense_title_required'),
                'title.max' => __('sentence.expense_title_max'),
                'from_account_id.required' => __('sentence.select_account_required'),
                'from_account_id.exists' => __('sentence.select_account_invalid'),
                'category_id.required' => __('sentence.select_category_required'),
                'category_id.exists' => __('sentence.select_category_invalid'),
                'amount.required' => __('sentence.amount_required'),
                'date.required' => __('sentence.date_required'),
                'description.max' => __('sentence.description_max'),
            ]
        );

        if ($validator->fails()) {
            toast()->error($validator->errors()->first());

            return redirect()
                ->back()
                ->withErrors($validator, 'expense')
                ->withInput();
        }

        try {
            $newAmount = $this->normalizeAmount($request->amount);

            if ($newAmount <= 0) {
                toast()->error(__('sentence.amount_must_be_positive'));

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => __('sentence.amount_must_be_positive'),
                    ], 'expense')
                    ->withInput();
            }

            $oldAccount = Account::where('id', $expense->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $newAccount = Account::where('id', $request->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $category = Category::where('id', $request->category_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            DB::beginTransaction();

            $oldAccount->increment('balance', $expense->amount);

            $newAccount->refresh();

            if ($newAmount > $newAccount->balance) {
                DB::rollBack();

                $insufficientBalanceMessage = __('sentence.insufficient_balance', [
                    'balance' => number_format($newAccount->balance, 0, ',', '.'),
                ]);

                toast()->error($insufficientBalanceMessage);

                return redirect()
                    ->back()
                    ->withErrors([
                        'amount' => $insufficientBalanceMessage,
                    ], 'expense')
                    ->withInput();
            }

            $newAccount->decrement('balance', $newAmount);

            $expense->update([
                'title' => $request->title,
                'amount' => $newAmount,
                'from_account_id' => $newAccount->id,
                'category_id' => $category->id,
                'date' => $this->normalizeDate($request->date),
                'description' => $request->description,
            ]);

            DB::commit();

            toast()->success(__('sentence.expense_updated'));

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error(__('sentence.expense_update_failed'));

            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $expense = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'expense')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            Account::where('id', $expense->from_account_id)
                ->where('user_id', Auth::id())
                ->increment('balance', $expense->amount);

            $expense->delete();

            DB::commit();

            toast()->success(__('sentence.expense_deleted'));

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error(__('sentence.expense_delete_failed'));

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
