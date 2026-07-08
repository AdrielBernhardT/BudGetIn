<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $now = Carbon::now();
            $userId = Auth::id();

            $categories = Category::where('user_id', $userId)
                ->withSum([
                    'transaction as expense_this_month' => function ($query) use ($now, $userId) {
                        $query->where('user_id', $userId)
                            ->where('type', 'expense')
                            ->whereMonth('date', $now->month)
                            ->whereYear('date', $now->year);
                    }
                ], 'amount')
                ->get()
                ->map(function ($category) {
                    $expense = (float) $category->expense_this_month ?? 0;
                    $budget = (float) $category->monthly_budget;

                    $usage = $budget > 0
                        ? (int) round(($expense / $budget) * 100)
                        : 0;

                    $category->expense_this_month = $expense;
                    $category->usage = $usage;
                    $category->remaining = max(0, $category->monthly_budget - $expense);

                    return $category;
                });

            confirmDelete('Are you sure you want to delete this category?');


            // dd($categories);
            return view(
                'pages.transaction.category',
                compact('categories')
            )->with('title', 'Category');

        } catch (\Throwable $th) {
            report($th);

            toast()->error('Failed to load categories.');

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->merge([
            'monthly_budget' => $this->normalizeAmount($request->monthly_budget),
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('categories', 'name')
                        ->where('user_id', Auth::id()),
                ],
                'monthly_budget' => ['required', 'numeric', 'min:0'],
                'icon' => ['required'],
            ],
            [
                'name.required' => 'Category name is required.',
                'name.max' => 'Category name may not exceed 100 characters.',
                'name.unique' => 'You already have a category with this name.',
                'monthly_budget.required' => 'Monthly budget is required.',
                'monthly_budget.numeric' => 'Monthly budget must be a number.',
                'monthly_budget.min' => 'Monthly budget cannot be negative.',
                'icon.required' => 'Please select an icon.',
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
            Category::create([
                'name' => $request->name,
                'icon' => $request->icon,
                'monthly_budget' => $request->monthly_budget,
                'user_id' => Auth::id(),
                'slug' => Category::generateSlug($request->name),
            ]);

            toast()->success('Category created successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to create category.'
            );

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $slug)
    {
        try {
            $category = Category::where('slug', $slug)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $request->merge([
                'monthly_budget' => $this->normalizeAmount($request->monthly_budget),
            ]);

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'string',
                        'max:100',
                        Rule::unique('categories', 'name')
                            ->where('user_id', Auth::id())
                            ->ignore($category->id),
                    ],
                    'monthly_budget' => ['required', 'numeric', 'min:0'],
                    'icon' => ['required'],
                ],
                [
                    'name.required' => 'Category name is required.',
                    'name.max' => 'Category name may not exceed 100 characters.',
                    'name.unique' => 'You already have a category with this name.',
                    'monthly_budget.required' => 'Monthly budget is required.',
                    'monthly_budget.numeric' => 'Monthly budget must be a number.',
                    'monthly_budget.min' => 'Monthly budget cannot be negative.',
                    'icon.required' => 'Please select an icon.',
                ]
            );

            if ($validator->fails()) {
                toast()->error($validator->errors()->first());

                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $category->update([
                'name' => $request->name,
                'icon' => $request->icon,
                'monthly_budget' => $request->monthly_budget,
                'slug' => Category::generateSlug($request->name),
            ]);

            toast()->success('Category updated successfully!');

            return redirect()->back();

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to update category.'
            );

            return redirect()->back()->withInput();
        }
    }

    public function destroy($slug)
    {
        try {
            $category = Category::where('slug', $slug)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $category->delete();

            toast()->success('Category deleted successfully!');

            return redirect()->route('category.index');

        } catch (\Throwable $th) {
            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to delete category.'
            );

            return redirect()->back();
        }
    }

    private function normalizeAmount($amount): int
    {
        return (int) preg_replace('/[^0-9]/', '', $amount);
    }
}