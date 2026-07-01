<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::where('user_id', Auth::id())->get();
            confirmDelete('Are you sure you want to delete this category?');
            return view(
                'pages.transaction.category',
                ['title' => 'Category'],
                compact('categories')
            );
        } catch (\Throwable $th) {
            report($th);
            toast()->error('Failed to load categories.');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'string',
                        'max:100',
                        'unique:categories,name,NULL,id,user_id,' . Auth::id()
                    ],
                    'monthly_budget' => ['required', 'numeric', 'min:0'],
                    'icon' => ['required']
                ],
                [
                    'name.required' => 'Category name is required.',
                    'name.unique' => 'You already have a category with this name.',
                    'monthly_budget.required' => 'Monthly budget is required.',
                    'monthly_budget.numeric' => 'Monthly budget must be a number.',
                    'icon.required' => 'Please select an icon.'
                ]
            );

            if ($validator->fails()) {

                toast()->error($validator->errors()->first());

                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

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

            return back()->withInput();
        }
    }

    public function update(Request $request, $slug)
    {
        try {

            $category = Category::where('slug', $slug)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => [
                        'required',
                        'string',
                        'max:100',
                        'unique:categories,name,' . $category->id . ',id,user_id,' . Auth::id()
                    ],
                    'monthly_budget' => ['required', 'numeric', 'min:0'],
                    'icon' => ['required']
                ],
                [
                    'name.required' => 'Category name is required.',
                    'name.unique' => 'You already have a category with this name.',
                    'monthly_budget.required' => 'Monthly budget is required.',
                    'monthly_budget.numeric' => 'Monthly budget must be a number.',
                    'icon.required' => 'Please select an icon.'
                ]
            );

            if ($validator->fails()) {

                toast()->error($validator->errors()->first());

                return back()
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

            return back()->withInput();
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

            return back();
        }
    }
}