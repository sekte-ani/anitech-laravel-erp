<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\ExpenseArchive;
use Illuminate\Console\Command;
use App\Models\DashboardExpense;
use Illuminate\Support\Facades\DB;

class ResetMonthlyExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expenses:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset table expenses setiap awal bulan dan arsipkan data sebelumnya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $now = Carbon::now();
            $previousMonth = $now->subMonth();
            
            $lastMonthDashboard = DashboardExpense::where('month', $previousMonth->month)
                ->where('year', $previousMonth->year)
                ->first();

            $startingBalance = $lastMonthDashboard ? $lastMonthDashboard->balance : 0;

            Expense::all()->each(function ($expense) use ($previousMonth) {
                ExpenseArchive::create([
                    'date' => $expense->date,
                    'item' => $expense->item,
                    'category_id' => $expense->category_id,
                    'type' => $expense->type,
                    'frequency' => $expense->frequency,
                    'amount' => $expense->amount,
                    'dashboard_id' => $expense->dashboard_id,
                    'month' => $previousMonth->month,
                    'year' => $previousMonth->year,
                ]);
            });

            Expense::truncate();

            DashboardExpense::create([
                'total_income' => 0,
                'total_expense' => 0,
                'balance' => $startingBalance,
                'month' => $now->month,
                'year' => $now->year
            ]);
        });

        $this->info('Reset bulanan selesai! Data bulan sebelumnya telah diarsipkan.');
    }

    public function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        $schedule->command(static::class)->monthlyOn(1, '00:00');
    }
}
