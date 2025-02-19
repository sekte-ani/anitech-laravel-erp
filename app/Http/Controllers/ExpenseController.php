<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();

        return view('content.erp.erp-finance-expanses', compact([
            'expenses',
        ]));
    }
}
