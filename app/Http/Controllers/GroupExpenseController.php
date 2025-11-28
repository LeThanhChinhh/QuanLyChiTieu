<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\GroupExpense;
use App\Services\GroupExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(GroupExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        // Exclude settlement expenses from the list
        $expenses = $group->expenses()
            ->where('is_settlement', false)
            ->with(['paidBy', 'category', 'splits.user'])
            ->latest('expense_date')
            ->paginate(20);

        return view('groups.expenses.index', compact('group', 'expenses'));
    }

    public function create($groupId)
    {
        $group = Group::with('activeMembers.user')->findOrFail($groupId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->where('type', 'expense')
            ->get();

        return view('groups.expenses.create', compact('group', 'categories'));
    }

    public function store(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        $validated = $request->validate([
            'paid_by_user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'expense_date' => 'required|date',
            'split_method' => 'required|in:equal,percentage,custom,shares',
            'members' => 'required|array|min:1',
            'members.*.user_id' => 'required|exists:users,id',
            'members.*.amount' => 'required_if:split_method,custom|nullable|numeric|min:0',
            'members.*.percentage' => 'required_if:split_method,percentage|nullable|numeric|min:0|max:100',
            'members.*.shares' => 'required_if:split_method,shares|nullable|integer|min:1',
        ]);

        // Validate paid_by_user is a member of the group
        if (!$group->isMember($validated['paid_by_user_id'])) {
            return back()->withInput()->with('error', 'Người trả phải là thành viên của nhóm');
        }

        try {
            $expense = $this->expenseService->addExpense([
                'group_id' => $groupId,
                'paid_by_user_id' => $validated['paid_by_user_id'],
                'category_id' => $validated['category_id'] ?? null,
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'expense_date' => $validated['expense_date'],
                'split_method' => $validated['split_method'],
                'members' => $validated['members'],
            ]);

            return redirect()->route('groups.show', $groupId)
                ->with('success', 'Đã thêm chi tiêu thành công!');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($groupId, $expenseId)
    {
        $group = Group::findOrFail($groupId);
        $expense = GroupExpense::with(['paidBy', 'category', 'splits.user'])
            ->where('group_id', $groupId)
            ->findOrFail($expenseId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        return view('groups.expenses.show', compact('group', 'expense'));
    }

    public function destroy($groupId, $expenseId)
    {
        $group = Group::findOrFail($groupId);
        $expense = GroupExpense::where('group_id', $groupId)
            ->findOrFail($expenseId);

        // Only the person who paid or admin can delete
        if ($expense->paid_by_user_id !== Auth::id() && !$group->isAdmin(Auth::id())) {
            abort(403, 'Không có quyền xóa chi tiêu này');
        }

        // Check if any splits are paid
        if ($expense->splits()->where('is_paid', true)->where('user_id', '!=', $expense->paid_by_user_id)->exists()) {
            return back()->with('error', 'Không thể xóa chi tiêu đã có người thanh toán');
        }

        $expense->delete();

        // Recalculate balances
        $this->expenseService->updateGroupBalances($groupId);

        return redirect()->route('groups.show', $groupId)
            ->with('success', 'Đã xóa chi tiêu');
    }
}
