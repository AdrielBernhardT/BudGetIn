<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::where('user_id', Auth::id())->get();
        confirmDelete('Are you sure you want to delete this category?');
        return view('pages.transaction.category', ['title' => 'Category'], compact('categories'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name'=> ['required', 'string', 'max:100', 'unique:categories,name,NULL,id,user_id,' . Auth::id()],
            'monthly_budget'=> ['required'],
            'icon' => ['required']
        ]);

        Category::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'monthly_budget' => $validated['monthly_budget'],
            'user_id' => Auth::id(),
            'slug' => Category::generateSlug($validated['name'])
        ]);

        toast()->success('Category created!');
        return redirect()->back()->with('success', 'Category created!');
    }

    public function update(Request $request, $slug){
        $category = Category::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name'=> ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id . ',id,user_id,' . Auth::id()],
            'monthly_budget'=> ['required'],
            'icon'=> ['required']
        ]);

        $category->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'monthly_budget' => $validated['monthly_budget'],
            'user_id' => Auth::id(),
            'slug' => Category::generateSlug($validated['name'])
        ]);

        toast()->success('Category updated!');
        return redirect()->back()->with('success', 'Category updated!');;
    }

    public function destroy($slug){
        Category::where('slug', $slug)->delete();

        toast()->success('Category deleted!');
        return redirect()->route('category.index');
    }


}
