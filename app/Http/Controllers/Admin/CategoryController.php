<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Lấy danh mục toàn cục (user_id = null)
        $categories = Category::whereNull('user_id')
                        ->orderBy('type')
                        ->orderBy('name')
                        ->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        Category::create([
            'user_id' => null, // Global category
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon ?? 'ri-price-tag-3-line',
            'color' => $request->color ?? '#6B7280',
        ]);

        return back()->with('success', 'Đã thêm danh mục mẫu thành công.');
    }

    public function update(Request $request, Category $category)
    {
        // Chỉ cho phép sửa danh mục global
        if ($category->user_id !== null) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $category->update($request->all());

        return back()->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== null) abort(403);
        
        $category->delete();
        return back()->with('success', 'Đã xóa danh mục mẫu.');
    }
}
