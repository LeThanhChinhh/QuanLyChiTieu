<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // TỐI ƯU: Chỉ dùng 1 câu truy vấn để lấy danh mục kèm tổng tiền và số lượng
    $categories = Category::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereNull('user_id');
        })
        ->withCount('transactions') // Đếm số giao dịch
        ->withSum(['transactions as monthly_total' => function($query) use ($currentMonth, $currentYear) {
            // Chỉ tính tổng tiền của tháng này
            $query->whereMonth('transaction_date', $currentMonth)
                  ->whereYear('transaction_date', $currentYear);
        }], 'amount')
        ->orderByRaw('user_id IS NULL DESC') // Ưu tiên danh mục hệ thống lên trước (hoặc sau tùy ý, ở đây để hệ thống lên trước)
        ->latest()
        ->get();

    return view('categories.index', compact('categories'));
}

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
{
    // Validate thêm các trường mới
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:income,expense',
        'icon' => 'nullable|string',
        'color' => 'nullable|string', // Dạng hex color
    ]);

    Category::create([
        'user_id' => Auth::id(),
        'name' => $request->name,
        'type' => $request->type,
        'icon' => $request->icon ?? 'ri-price-tag-3-line', // Icon mặc định nếu không chọn
        'color' => $request->color ?? '#6B7280', // Màu xám mặc định
    ]);

    return redirect()->route('categories.index')->with('success', 'Đã thêm danh mục mới!');
}

    // --- PHẦN MỚI: SỬA & XÓA ---

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Đã xóa danh mục!');
    }
}