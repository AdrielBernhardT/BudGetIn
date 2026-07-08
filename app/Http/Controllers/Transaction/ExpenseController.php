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

class ExpenseController extends Controller
{
    public function index()
    {
        try {
            $expenses = Transaction::with(['fromAccount', 'category'])
                ->where('user_id', Auth::id())
                ->where('type', 'expense')
                ->get();

            $categories = Category::where('user_id', Auth::id())->get();

            $accounts = Account::where('user_id', Auth::id())->get();

            confirmDelete('Are you sure you want to delete this expense?');

            return view(
                'pages.transaction.expense',
                ['title' => 'Expense'],
                compact('expenses', 'categories', 'accounts')
            );

        } catch (\Throwable $th) {

            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to load expenses.'
            );

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validateWithBag('expense', [
                'title' => ['required', 'string', 'max:100'],
                'from_account_id' => ['required'],
                'category_id' => ['required'],
                'amount' => ['required', 'numeric', 'min:1'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ], [
                'title.required' => 'Expense title is required.',
                'from_account_id.required' => 'Please select an account.',
                'category_id.required' => 'Please select a category.',
                'amount.required' => 'Amount is required.',
                'amount.numeric' => 'Amount must be numeric.',
                'amount.min' => 'Amount must be greater than 0.',
                'date.required' => 'Date is required.',
            ]);

            $userId = Auth::id();

            $fromAccount = Account::where('id', $validated['from_account_id'])
                ->where('user_id', $userId)
                ->first();

            if (!$fromAccount) {

                toast()->error('Invalid account selected.');

                return back()
                    ->withErrors([
                        'from_account_id' => 'Invalid account selected.'
                    ], 'expense')
                    ->withInput();
            }

            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', $userId)
                ->first();

            if (!$category) {

                toast()->error('Invalid category selected.');

                return back()
                    ->withErrors([
                        'category_id' => 'Invalid category selected.'
                    ], 'expense')
                    ->withInput();
            }

            $amount = (int) $validated['amount'];

            if ($amount > $fromAccount->balance) {

                toast()->error(
                    'Insufficient balance. Current balance: ' .
                    number_format($fromAccount->balance, 0, ',', '.')
                );

                return back()
                    ->withErrors([
                        'amount' =>
                            'Insufficient balance. Current balance: ' .
                            number_format($fromAccount->balance, 0, ',', '.')
                    ], 'expense')
                    ->withInput();
            }

            DB::beginTransaction();

            $fromAccount->decrement('balance', $amount);

            Transaction::create([
                'user_id' => $userId,
                'type' => 'expense',
                'title' => $validated['title'],
                'amount' => $amount,
                'from_account_id' => $fromAccount->id,
                'category_id' => $category->id,
                'date' => Carbon::createFromFormat(
                    'd-m-Y',
                    $validated['date']
                )->format('Y-m-d'),
                'description' => $validated['description'],
            ]);

            DB::commit();

            toast()->success('Expense created successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {

            DB::rollBack();

            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to create expense.'
            );

            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $userId = Auth::id();

            $expense = Transaction::where('id', $id)
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->firstOrFail();

            $validated = $request->validateWithBag('expense', [
                'title' => ['required', 'string', 'max:100'],
                'from_account_id' => ['required'],
                'category_id' => ['required'],
                'amount' => ['required', 'numeric', 'min:1'],
                'date' => ['required'],
                'description' => ['nullable', 'string', 'max:200'],
            ], [
                'title.required' => 'Expense title is required.',
                'from_account_id.required' => 'Please select an account.',
                'category_id.required' => 'Please select a category.',
                'amount.required' => 'Amount is required.',
                'amount.numeric' => 'Amount must be numeric.',
                'amount.min' => 'Amount must be greater than 0.',
                'date.required' => 'Date is required.',
            ]);

            $oldAccount = Account::where('id', $expense->from_account_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $newAccount = Account::where('id', $validated['from_account_id'])
                ->where('user_id', $userId)
                ->first();

            if (!$newAccount) {

                toast()->error('Invalid account selected.');

                return back()
                    ->withErrors([
                        'from_account_id' => 'Invalid account selected.'
                    ], 'expense')
                    ->withInput();
            }

            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', $userId)
                ->first();

            if (!$category) {

                toast()->error('Invalid category selected.');

                return back()
                    ->withErrors([
                        'category_id' => 'Invalid category selected.'
                    ], 'expense')
                    ->withInput();
            }

            $newAmount = (int) $validated['amount'];

            DB::beginTransaction();

            $oldAccount->increment('balance', $expense->amount);

            $newAccount->refresh();

            if ($newAccount->balance < $newAmount) {

                DB::rollBack();

                toast()->error(
                    'Insufficient balance. Current balance: ' .
                    number_format($newAccount->balance, 0, ',', '.')
                );

                return back()
                    ->withErrors([
                        'amount' =>
                            'Insufficient balance. Current balance: ' .
                            number_format($newAccount->balance, 0, ',', '.')
                    ], 'expense')
                    ->withInput();
            }

            $newAccount->decrement('balance', $newAmount);

            $expense->update([
                'title' => $validated['title'],
                'amount' => $newAmount,
                'from_account_id' => $newAccount->id,
                'category_id' => $category->id,
                'date' => Carbon::createFromFormat(
                    'd-m-Y',
                    $validated['date']
                )->format('Y-m-d'),
                'description' => $validated['description'],
            ]);

            DB::commit();

            toast()->success('Expense updated successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {

            DB::rollBack();

            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to update expense.'
            );

            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $expense = Transaction::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('type', 'expense')
                ->firstOrFail();

            $account = Account::where('id', $expense->from_account_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $account->increment('balance', $expense->amount);

            $expense->delete();

            DB::commit();

            toast()->success('Expense deleted successfully!');
            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            toast()->error('Failed to delete expense.');
            return redirect()->back();
        }
    }
}