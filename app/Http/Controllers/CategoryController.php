<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ActivityLog, Category};

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ], [
            'category_name.unique' => 'Category name is already exist, please use a different name!',
        ]);

        $category = Category::create([
            'category_name' => $request->category_name,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created category: ' . $category->category_name
        ]);

        return redirect()->route('categories.index')->with(['success' => 'Category created successfully!']);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $id,
        ], [
            'category_name'=> 'Category name is already exist, please use a different name!',
        ]);

        $category->update([
            'category_name' => $request->category_name,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated category: ' . $category->category_name
        ]);

        return redirect()->route('categories.index')->with(['success' => 'Category updated successfully!']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $categoryName = $category->category_name;
        $category->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted category: ' . $categoryName
        ]);

        return redirect()->route('categories.index')->with(['success' => 'Category deleted successfully!']);
    }
}
