<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\CategoryExpense;
use App\Models\DashboardExpense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $date = explode('-', $selectedMonth);
        $selectedYear = $date[0];
        $selectedMonthNum = $date[1];

        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');

        if ($selectedMonthNum == $currentMonth && $selectedYear == $currentYear) {
            $expenses = Expense::whereMonth('date', $selectedMonthNum)->whereYear('date', $selectedYear)->orderBy('date')->get();
        } else {
            $expenses = DB::table('expenses_archive')->whereMonth('date', $selectedMonthNum)->whereYear('date', $selectedYear)->orderBy('date')->get();
        }

        $category = CategoryExpense::all();
        $type = ['Pengeluaran', 'Pemasukan'];
        $frequency = ['Project', 'Bulanan', 'Satu Kali', 'Tahunan'];

        return view('content.erp.erp-finance-expanses', compact([
            'expenses',
            'category',
            'type',
            'frequency',
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'item' => 'required|string',
            'category_id' => 'required|exists:category_expenses,id',
            'type' => 'required|in:Pengeluaran,Pemasukan',
            'frequency' => 'required|in:Project,Bulanan,Satu Kali,Tahunan',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $category = CategoryExpense::findOrFail($request->category_id);

        $expenseMonth = date('m', strtotime($request->date));
        $expenseYear = date('Y', strtotime($request->date));

        $dashboard = DashboardExpense::firstOrCreate(
            ['month' => $expenseMonth, 'year' => $expenseYear],
            ['total_income' => 0, 'total_expense' => 0, 'balance' => 0]
        );

        $previousMonth = $expenseMonth - 1;
        $previousYear = $expenseYear;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousYear -= 1;
        }

        $previousBalance = DashboardExpense::where('month', $previousMonth)->where('year', $previousYear)->value('balance') ?? 0;

        $amount = (strtolower($category->name) === 'sisa saldo') ? $previousBalance : $request->amount;

        $balanceChange = ($request->type === 'Pemasukan') ? $amount : -$amount;

        $expense = Expense::create([
            'dashboard_id' => $dashboard->id,
            'date' => $request->date,
            'item' => $request->item,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'amount' => $amount,
            'balance' => $dashboard->balance + $balanceChange,
        ]);

        if ($request->type === 'Pemasukan') {
            $dashboard->increment('total_income', $amount);
        } else {
            $dashboard->increment('total_expense', $amount);
        }

        $dashboard->increment('balance', $balanceChange);
        $dashboard->save();

        return redirect()->back()->with('success', 'Expense berhasil ditambahkan!');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'item' => 'required|string',
            'category_id' => 'required|exists:category_expenses,id',
            'type' => 'required|in:Pengeluaran,Pemasukan',
            'frequency' => 'required|in:Project,Bulanan,Satu Kali,Tahunan',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $category = CategoryExpense::findOrFail($request->category_id);
        $expenseMonth = date('m', strtotime($request->date));
        $expenseYear = date('Y', strtotime($request->date));

        $dashboard = DashboardExpense::firstOrCreate(
            ['month' => $expenseMonth, 'year' => $expenseYear],
            ['total_income' => 0, 'total_expense' => 0, 'balance' => 0]
        );

        $oldAmount = (float) $expense->amount;
        $oldType = $expense->type;

        if (strtolower($category->name) === 'sisa saldo') {
            $previousBalance = DashboardExpense::where('month', $expenseMonth - 1)->where('year', $expenseYear)->value('balance') ?? 0;
            $amount = $previousBalance;
        } else {
            $amount = (float) $request->amount;
        }

        if ($oldType === 'Pemasukan') {
            $dashboard->decrement('total_income', $oldAmount);
            $dashboard->decrement('balance', $oldAmount);
        } else {
            $dashboard->decrement('total_expense', $oldAmount);
            $dashboard->increment('balance', $oldAmount);
        }

        if ($request->type === 'Pemasukan') {
            $dashboard->increment('total_income', $amount);
            $dashboard->increment('balance', $amount);
        } else {
            $dashboard->increment('total_expense', $amount);
            $dashboard->decrement('balance', $amount);
        }

        $expense->update([
            'date' => $request->date,
            'item' => $request->item,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'amount' => $amount,
            'balance' => $dashboard->balance,
        ]);

        $dashboard->save();

        $this->updateFollowingBalances($dashboard->id, $expense->date, $dashboard->balance);

        return redirect()->back()->with('success', 'Expense berhasil diperbarui!');
    }

    private function updateFollowingBalances($dashboardId, $startDate, $updatedBalance)
    {
        $expenses = Expense::where('dashboard_id', $dashboardId)->where('date', '>', $startDate)->orderBy('date', 'asc')->get();

        $previousBalance = $updatedBalance;

        foreach ($expenses as $expense) {
            $newBalance = ($expense->type === 'Pemasukan') 
                ? $previousBalance + $expense->amount 
                : $previousBalance - $expense->amount;

            $expense->update(['balance' => $newBalance]);
            $previousBalance = $newBalance;
        }
    }

    public function destroy(Expense $expense)
    {
        $amount = $expense->amount;
        $type = $expense->type;

        $dashboard = DashboardExpense::where('month', date('m', strtotime($expense->date)))
            ->where('year', date('Y', strtotime($expense->date)))
            ->first();

        if ($dashboard) {
            if ($type === 'Pemasukan') {
                $dashboard->decrement('total_income', $amount);
            } else {
                $dashboard->decrement('total_expense', $amount);
            }
            $dashboard->decrement('balance', ($type === 'Pemasukan') ? $amount : -$amount);
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Expense berhasil dihapus!');
    }
}
