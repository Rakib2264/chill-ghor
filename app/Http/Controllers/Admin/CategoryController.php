<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:80',
            'emoji' => 'nullable|string|max:8',
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        $data['sort_order'] = (Category::max('sort_order') ?? 0) + 1;

        Category::create($data);
        return back()->with('toast', '✅ ক্যাটাগরি যোগ হয়েছে');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:80',
            'emoji' => 'nullable|string|max:8',
        ]);
        $category->update($data);
        return back()->with('toast', '✅ আপডেট হয়েছে');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('toast', '⚠️ এই ক্যাটাগরিতে পণ্য আছে — আগে সরান');
        }
        $category->delete();
        return back()->with('toast', '🗑️ মুছে ফেলা হয়েছে');
    }
}
