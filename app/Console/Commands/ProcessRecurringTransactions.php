<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\SystemAlert;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring transactions that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $recurringTransactions = RecurringTransaction::where('status', 'active')
            ->where('next_run_date', '<=', $today)
            ->get();

        $count = 0;

        foreach ($recurringTransactions as $recurring) {
            DB::transaction(function () use ($recurring) {
                // 1. Create Transaction
                $transaction = Transaction::create([
                    'user_id' => $recurring->user_id,
                    'wallet_id' => $recurring->wallet_id,
                    'destination_wallet_id' => $recurring->destination_wallet_id,
                    'category_id' => $recurring->category_id,
                    'amount' => $recurring->amount,
                    'type' => $recurring->type,
                    'transaction_date' => $recurring->next_run_date, // Use the scheduled date
                    'description' => $recurring->description . ' (Recurring)',
                ]);

                // 2. Update Wallet Balance
                $sourceWallet = Wallet::find($recurring->wallet_id);
                $amount = (float) $recurring->amount;

                if ($recurring->type == 'income') {
                    $sourceWallet->increment('balance', $amount);
                } elseif ($recurring->type == 'expense') {
                    $sourceWallet->decrement('balance', $amount);
                } elseif ($recurring->type == 'transfer') {
                    $sourceWallet->decrement('balance', $amount);
                    if ($recurring->destination_wallet_id) {
                        $destWallet = Wallet::find($recurring->destination_wallet_id);
                        if ($destWallet) {
                            $destWallet->increment('balance', $amount);
                        }
                    }
                }

                // 3. Update Next Run Date
                $nextDate = Carbon::parse($recurring->next_run_date);
                
                switch ($recurring->frequency) {
                    case 'daily':
                        $nextDate->addDay();
                        break;
                    case 'weekly':
                        $nextDate->addWeek();
                        break;
                    case 'monthly':
                        $nextDate->addMonth();
                        break;
                    case 'yearly':
                        $nextDate->addYear();
                        break;
                }

                $recurring->update(['next_run_date' => $nextDate]);

                // 4. Send Notification
                $recurring->user->notify(new SystemAlert(
                    'Recurring Transaction Processed',
                    "A recurring {$recurring->type} of " . number_format($recurring->amount) . " has been processed."
                ));
            });

            $count++;
        }

        $this->info("Processed {$count} recurring transactions.");
    }
}
