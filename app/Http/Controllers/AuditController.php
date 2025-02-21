<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Http\Request;
use App\Models\CategoryExpense;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        
        $query = Audit::with('user')->orderBy('created_at', 'desc');

        if ($month) {
            $query->whereMonth('created_at', Carbon::parse($month)->month)->whereYear('created_at', Carbon::parse($month)->year);
        }

        $audit = $query->get();

        $categories = CategoryExpense::pluck('name', 'id');

        return view('content.erp.erp-finance-audit', compact([
            'audit',
            'categories',
            'month',
        ]));
    }
}
