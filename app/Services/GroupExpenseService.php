<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupBalance;
use App\Models\GroupExpense;
use App\Models\GroupExpenseSplit;
use App\Models\GroupMember;
use App\Models\GroupSettlement;
use App\Notifications\SystemAlert;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GroupExpenseService
{
    /**
     * Add a new group expense with splits
     */
    public function addExpense(array $data): GroupExpense
    {
        return DB::transaction(function () use ($data) {
            // 1. Validate splits
            $this->validateSplits($data);

            // 2. Create expense
            $expense = GroupExpense::create([
                'group_id' => $data['group_id'],
                'paid_by_user_id' => $data['paid_by_user_id'],
                'category_id' => $data['category_id'] ?? null,
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null,
                'expense_date' => $data['expense_date'],
                'split_method' => $data['split_method'],
            ]);

            // 3. Calculate splits based on method
            $splits = $this->calculateSplits($data);

            // 4. Create splits
            foreach ($splits as $split) {
                GroupExpenseSplit::create([
                    'expense_id' => $expense->id,
                    'user_id' => $split['user_id'],
                    'amount' => $split['amount'],
                    'percentage' => $split['percentage'] ?? null,
                    'shares' => $split['shares'] ?? null,
                    'is_paid' => $split['user_id'] === $data['paid_by_user_id'],
                    'paid_at' => $split['user_id'] === $data['paid_by_user_id'] ? now() : null,
                ]);
            }

            // 5. Update balances
            $this->updateGroupBalances($data['group_id']);

            // 6. Check if fully settled
            $this->checkExpenseSettled($expense->id);

            // 7. Notify members
            $this->notifyMembers($expense);

            return $expense->load(['splits.user', 'paidBy', 'category']);
        });
    }

    /**
     * Calculate splits based on split method
     */
    private function calculateSplits(array $data): array
    {
        $amount = $data['amount'];
        $method = $data['split_method'];
        $members = $data['members']; // Array of user_ids or user data

        $splits = [];

        switch ($method) {
            case 'equal':
                $perPerson = round($amount / count($members), 2);
                $remainder = $amount - ($perPerson * count($members));

                foreach ($members as $index => $userId) {
                    $splitAmount = $perPerson;
                    // Add remainder to first person to handle rounding
                    if ($index === 0) {
                        $splitAmount += $remainder;
                    }
                    $splits[] = [
                        'user_id' => is_array($userId) ? $userId['user_id'] : $userId,
                        'amount' => $splitAmount,
                        'percentage' => round(($splitAmount / $amount) * 100, 2),
                    ];
                }
                break;

            case 'percentage':
                foreach ($members as $member) {
                    $percentage = $member['percentage'];
                    $splitAmount = round(($amount * $percentage) / 100, 2);
                    $splits[] = [
                        'user_id' => $member['user_id'],
                        'amount' => $splitAmount,
                        'percentage' => $percentage,
                    ];
                }
                break;

            case 'custom':
                foreach ($members as $member) {
                    $splits[] = [
                        'user_id' => $member['user_id'],
                        'amount' => $member['amount'],
                        'percentage' => round(($member['amount'] / $amount) * 100, 2),
                    ];
                }
                break;

            case 'shares':
                $totalShares = collect($members)->sum('shares');
                foreach ($members as $member) {
                    $splitAmount = round(($amount * $member['shares']) / $totalShares, 2);
                    $splits[] = [
                        'user_id' => $member['user_id'],
                        'amount' => $splitAmount,
                        'shares' => $member['shares'],
                        'percentage' => round(($member['shares'] / $totalShares) * 100, 2),
                    ];
                }
                break;
        }

        return $splits;
    }

    /**
     * Validate that splits equal total amount
     */
    private function validateSplits(array $data): void
    {
        if ($data['split_method'] === 'equal') {
            return; // Will be calculated automatically
        }

        $amount = $data['amount'];
        $members = $data['members'];

        if ($data['split_method'] === 'percentage') {
            $totalPercentage = collect($members)->sum('percentage');
            if (abs($totalPercentage - 100) > 0.01) {
                throw new InvalidArgumentException("Total percentage must equal 100%, got {$totalPercentage}%");
            }
        }

        if ($data['split_method'] === 'custom') {
            $totalSplits = collect($members)->sum('amount');
            if (abs($totalSplits - $amount) > 0.01) {
                throw new InvalidArgumentException("Total splits ({$totalSplits}) must equal expense amount ({$amount})");
            }
        }
    }

    /**
     * Update balances for all group members
     */
    public function updateGroupBalances(int $groupId): void
    {
        $members = GroupMember::where('group_id', $groupId)
            ->where('status', 'active')
            ->pluck('user_id');

        foreach ($members as $userId) {
            $balance = $this->calculateUserBalance($groupId, $userId);

            GroupBalance::updateOrCreate(
                ['group_id' => $groupId, 'user_id' => $userId],
                ['balance' => $balance, 'last_calculated_at' => now()]
            );
        }
    }

    /**
     * Calculate balance for a specific user in a group
     * 
     * Balance formula: What user paid - What user owes
     * Positive balance = User advanced money (others owe this user)
     * Negative balance = User owes money (user owes others)
     * 
     * Note: Settlements are NOT included in balance calculation.
     * They only mark splits as paid for tracking purposes.
     */
    public function calculateUserBalance(int $groupId, int $userId): float
    {
        // What user paid for the group
        $paid = GroupExpense::where('group_id', $groupId)
            ->where('paid_by_user_id', $userId)
            ->sum('amount');

        // What user owes (their share of all expenses)
        $owes = GroupExpenseSplit::whereHas('expense', function ($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })
            ->where('user_id', $userId)
            ->sum('amount');

        // Balance = Paid - Owes
        // If positive: user advanced money, should receive from others
        // If negative: user owes money, should pay to others
        // If zero: user is settled up
        return $paid - $owes;
    }

    /**
     * Settle payment between two users
     * 
     * Creates a settlement record and a compensating "settlement expense" 
     * to balance out the accounts properly.
     */
    public function settlePayment(int $groupId, int $fromUserId, int $toUserId, float $amount, ?string $notes = null): GroupSettlement
    {
        return DB::transaction(function () use ($groupId, $fromUserId, $toUserId, $amount, $notes) {
            // 1. Validate membership
            $this->validateMembership($groupId, [$fromUserId, $toUserId]);

            // 2. Validate amount
            if ($amount <= 0) {
                throw new InvalidArgumentException("Settlement amount must be positive");
            }

            // 3. Create settlement record
            $settlement = GroupSettlement::create([
                'group_id' => $groupId,
                'from_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'amount' => $amount,
                'settled_at' => now(),
                'notes' => $notes,
            ]);

            // 4. Create a compensating expense to balance accounts
            // This expense represents the settlement payment:
            // - fromUser pays the amount
            // - toUser owes the amount (receives the payment)
            $settlementExpense = GroupExpense::create([
                'group_id' => $groupId,
                'paid_by_user_id' => $fromUserId, // fromUser pays
                'category_id' => null,
                'amount' => $amount,
                'description' => $notes ?? 'Thanh toán số dư',
                'expense_date' => now(),
                'split_method' => 'custom',
                'is_settlement' => true, // Mark as settlement expense
            ]);

            // 5. Create split: toUser owes this amount
            GroupExpenseSplit::create([
                'expense_id' => $settlementExpense->id,
                'user_id' => $toUserId, // toUser owes (receives)
                'amount' => $amount,
                'is_paid' => true, // Already paid via settlement
                'paid_at' => now(),
            ]);

            // 6. Mark original splits as paid (FIFO)
            $this->markSplitsAsPaid($groupId, $fromUserId, $toUserId, $amount);

            // 7. Update balances
            $this->updateGroupBalances($groupId);

            // 8. Notify users
            $this->notifySettlement($settlement);

            return $settlement->load(['fromUser', 'toUser', 'group']);
        });
    }

    /**
     * Mark expense splits as paid based on settlement
     */
    private function markSplitsAsPaid(int $groupId, int $fromUserId, int $toUserId, float $amount): void
    {
        $remainingAmount = $amount;

        // Get unpaid splits where fromUser owes toUser (toUser paid the expense)
        $splits = GroupExpenseSplit::whereHas('expense', function ($q) use ($groupId, $toUserId) {
            $q->where('group_id', $groupId)
                ->where('paid_by_user_id', $toUserId);
        })
            ->where('user_id', $fromUserId)
            ->where('is_paid', false)
            ->orderBy('created_at')
            ->get();

        foreach ($splits as $split) {
            if ($remainingAmount <= 0) {
                break;
            }

            if ($split->amount <= $remainingAmount) {
                // Fully pay this split
                $split->update([
                    'is_paid' => true,
                    'paid_at' => now(),
                ]);
                $remainingAmount -= $split->amount;
            } else {
                // Partial payment - would need to split the split (not implemented)
                // For simplicity, we'll mark as paid if settlement covers most of it
                break;
            }
        }
    }

    /**
     * Check if expense is fully settled
     */
    private function checkExpenseSettled(int $expenseId): void
    {
        $expense = GroupExpense::find($expenseId);
        $unpaidCount = $expense->splits()->where('is_paid', false)->count();

        if ($unpaidCount === 0) {
            $expense->update(['is_settled' => true]);
        }
    }

    /**
     * Validate that users are members of the group
     */
    private function validateMembership(int $groupId, array $userIds): void
    {
        foreach ($userIds as $userId) {
            $isMember = GroupMember::where('group_id', $groupId)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->exists();

            if (!$isMember) {
                throw new InvalidArgumentException("User {$userId} is not an active member of this group");
            }
        }
    }

    /**
     * Calculate debt between two users
     */
    public function calculateDebtBetweenUsers(int $groupId, int $userId1, int $userId2): float
    {
        // What user1 paid for user2
        $user1PaidForUser2 = GroupExpenseSplit::whereHas('expense', function ($q) use ($groupId, $userId1) {
            $q->where('group_id', $groupId)
                ->where('paid_by_user_id', $userId1);
        })
            ->where('user_id', $userId2)
            ->where('is_paid', false)
            ->sum('amount');

        // What user2 paid for user1
        $user2PaidForUser1 = GroupExpenseSplit::whereHas('expense', function ($q) use ($groupId, $userId2) {
            $q->where('group_id', $groupId)
                ->where('paid_by_user_id', $userId2);
        })
            ->where('user_id', $userId1)
            ->where('is_paid', false)
            ->sum('amount');

        // Net debt: positive = user2 owes user1, negative = user1 owes user2
        return $user1PaidForUser2 - $user2PaidForUser1;
    }

    /**
     * Get simplified debts for a group (minimize transactions)
     */
    public function getSimplifiedDebts(int $groupId): array
    {
        $balances = GroupBalance::where('group_id', $groupId)
            ->with('user')
            ->get();

        $creditors = []; // Users who are owed money (positive balance)
        $debtors = [];   // Users who owe money (negative balance)

        foreach ($balances as $balance) {
            if ($balance->balance > 0.01) {
                $creditors[] = [
                    'user' => $balance->user,
                    'amount' => $balance->balance,
                ];
            } elseif ($balance->balance < -0.01) {
                $debtors[] = [
                    'user' => $balance->user,
                    'amount' => abs($balance->balance),
                ];
            }
        }

        // Sort by amount descending
        usort($creditors, fn($a, $b) => $b['amount'] <=> $a['amount']);
        usort($debtors, fn($a, $b) => $b['amount'] <=> $a['amount']);

        // Simplify transactions
        $transactions = [];
        $i = 0;
        $j = 0;

        while ($i < count($debtors) && $j < count($creditors)) {
            $debtor = &$debtors[$i];
            $creditor = &$creditors[$j];

            $amount = min($debtor['amount'], $creditor['amount']);

            $transactions[] = [
                'from' => $debtor['user'],
                'to' => $creditor['user'],
                'amount' => round($amount, 2),
            ];

            $debtor['amount'] -= $amount;
            $creditor['amount'] -= $amount;

            if ($debtor['amount'] < 0.01) {
                $i++;
            }
            if ($creditor['amount'] < 0.01) {
                $j++;
            }
        }

        return $transactions;
    }

    /**
     * Notify members about new expense
     */
    private function notifyMembers(GroupExpense $expense): void
    {
        $group = $expense->group;
        $paidBy = $expense->paidBy;

        $members = GroupMember::where('group_id', $expense->group_id)
            ->where('status', 'active')
            ->where('user_id', '!=', $expense->paid_by_user_id)
            ->with('user')
            ->get();

        foreach ($members as $member) {
            $split = GroupExpenseSplit::where('expense_id', $expense->id)
                ->where('user_id', $member->user_id)
                ->first();

            if ($split) {
                $message = "{$paidBy->name} đã thêm chi tiêu \"{$expense->description}\" trong nhóm \"{$group->name}\". Bạn cần trả: " . number_format($split->amount) . "đ";
                $member->user->notify(new SystemAlert($message));
            }
        }
    }

    /**
     * Notify users about settlement
     */
    private function notifySettlement(GroupSettlement $settlement): void
    {
        $message1 = "{$settlement->fromUser->name} đã thanh toán cho bạn " . number_format($settlement->amount) . "đ trong nhóm \"{$settlement->group->name}\"";
        $settlement->toUser->notify(new SystemAlert($message1));

        $message2 = "Bạn đã thanh toán " . number_format($settlement->amount) . "đ cho {$settlement->toUser->name} trong nhóm \"{$settlement->group->name}\"";
        $settlement->fromUser->notify(new SystemAlert($message2));
    }
}
