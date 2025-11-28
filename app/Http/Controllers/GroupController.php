<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Services\GroupExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    protected $expenseService;

    public function __construct(GroupExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }
    public function index()
    {
        $user = Auth::user();
        $groups = $user->activeGroups()
            ->withCount([
                'members' => function($query) {
                    $query->where('status', 'active');
                },
                'expenses'
            ])
            ->latest()
            ->get();

        return view('groups.index', compact('groups'));
    }

    public function show($id)
    {
        $group = Group::with(['activeMembers.user', 'expenses.paidBy', 'expenses.category'])
            ->findOrFail($id);

        // Check if user is member
        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        // Exclude settlement expenses from recent expenses
        $recentExpenses = $group->expenses()
            ->where('is_settlement', false)
            ->with(['paidBy', 'category', 'splits.user'])
            ->latest('expense_date')
            ->limit(10)
            ->get();

        // Get all regular expenses (exclude settlements) for expenses tab
        $allExpenses = $group->expenses()
            ->where('is_settlement', false)
            ->with(['paidBy', 'category', 'splits.user'])
            ->latest('expense_date')
            ->get();

        // Get all balances for balances tab
        $balances = $group->balances()
            ->with('user')
            ->get();

        $balance = $group->balances()
            ->where('user_id', Auth::id())
            ->first();

        // Calculate simplified debts for settlements
        $simplifiedDebts = $this->expenseService->getSimplifiedDebts($id);

        // Get settlement history (latest 5)
        $settlements = $group->settlements()
            ->with(['fromUser', 'toUser'])
            ->latest('settled_at')
            ->limit(5)
            ->get();

        return view('groups.show', compact('group', 'recentExpenses', 'allExpenses', 'balances', 'balance', 'simplifiedDebts', 'settlements'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'currency' => 'nullable|string|max:3',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['icon'] = $validated['icon'] ?? 'ri-team-line';
        $validated['color'] = $validated['color'] ?? '#10B981';
        $validated['currency'] = $validated['currency'] ?? 'VND';

        $group = Group::create($validated);

        // Add creator as admin member
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Nhóm đã được tạo thành công!');
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);

        if (!$group->isAdmin(Auth::id())) {
            abort(403, 'Chỉ admin mới có thể chỉnh sửa nhóm');
        }

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if (!$group->isAdmin(Auth::id())) {
            abort(403, 'Chỉ admin mới có thể chỉnh sửa nhóm');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $group->update($validated);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Nhóm đã được cập nhật!');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        if ($group->created_by !== Auth::id()) {
            abort(403, 'Chỉ người tạo nhóm mới có thể xóa nhóm');
        }

        try {
            DB::transaction(function () use ($group) {
                // Delete all settlements
                $group->settlements()->delete();
                
                // Delete all expense splits
                foreach ($group->expenses as $expense) {
                    $expense->splits()->delete();
                }
                
                // Delete all expenses
                $group->expenses()->delete();
                
                // Delete all balances
                $group->balances()->delete();
                
                // Delete all members
                $group->members()->delete();
                
                // Finally delete the group
                $group->delete();
            });

            return redirect()->route('groups.index')
                ->with('success', 'Nhóm đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa nhóm: ' . $e->getMessage());
        }
    }
}
