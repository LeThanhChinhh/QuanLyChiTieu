<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupSettlementController extends Controller
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

        // Get balances
        $balances = $group->balances()
            ->with('user')
            ->get()
            ->sortByDesc(fn($b) => abs($b->balance));

        // Get simplified debts
        $simplifiedDebts = $this->expenseService->getSimplifiedDebts($groupId);

        // Get recent settlements
        $settlements = $group->settlements()
            ->with(['fromUser', 'toUser'])
            ->latest('settled_at')
            ->paginate(20);

        return view('groups.settlements.index', compact('group', 'balances', 'simplifiedDebts', 'settlements'));
    }

    public function create($groupId)
    {
        $group = Group::with('activeMembers.user')->findOrFail($groupId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        return view('groups.settlements.create', compact('group'));
    }

    public function store(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'amount_raw' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
        ]);

        // Use amount_raw (the unformatted number)
        $amount = $validated['amount_raw'];

        // Validate recipient is a member
        if (!$group->isMember($validated['to_user_id'])) {
            return back()->with('error', 'Người nhận không phải là thành viên của nhóm');
        }

        // Cannot settle with yourself
        if ($validated['to_user_id'] == Auth::id()) {
            return back()->with('error', 'Không thể thanh toán cho chính mình');
        }

        try {
            $settlement = $this->expenseService->settlePayment(
                $groupId,
                Auth::id(),
                $validated['to_user_id'],
                $amount,
                $validated['notes'] ?? null
            );

            return redirect()->route('groups.settlements.index', $groupId)
                ->with('success', 'Đã ghi nhận thanh toán thành công!');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($groupId, $settlementId)
    {
        $group = Group::findOrFail($groupId);
        $settlement = $group->settlements()->findOrFail($settlementId);

        // Only the payer or admin can delete
        if ($settlement->from_user_id !== Auth::id() && !$group->isAdmin(Auth::id())) {
            abort(403, 'Không có quyền xóa thanh toán này');
        }

        $settlement->delete();

        // Recalculate balances
        $this->expenseService->updateGroupBalances($groupId);

        return back()->with('success', 'Đã xóa ghi nhận thanh toán');
    }
}
