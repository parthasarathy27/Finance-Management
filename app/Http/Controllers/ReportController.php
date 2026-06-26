<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Budget;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Monthly Sales vs Purchases (past 6 months)
        $salesData = [];
        $purchaseData = [];
        $monthLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('M Y');

            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $salesData[] = SalesInvoice::whereBetween('invoice_date', [$start, $end])->sum('total_amount');
            $purchaseData[] = PurchaseInvoice::whereBetween('invoice_date', [$start, $end])->sum('total_amount');
        }

        // 2. Budget vs Actual Spend
        $budgets = Budget::all();
        $budgetLabels = [];
        $budgetLimits = [];
        $budgetSpent = [];

        foreach ($budgets as $budget) {
            $budgetLabels[] = $budget->category_name;
            $budgetLimits[] = $budget->amount;
            $budgetSpent[] = $budget->spent_amount;
        }

        // 3. Sales Invoices status distribution
        $salesPaid = SalesInvoice::where('status', 'Paid')->count();
        $salesPending = SalesInvoice::where('status', 'Pending')->count();
        $salesOverdue = SalesInvoice::where('status', 'Overdue')->count();

        // 4. Purchase Invoices status distribution
        $purchasePaid = PurchaseInvoice::where('status', 'Paid')->count();
        $purchasePending = PurchaseInvoice::where('status', 'Pending')->count();
        $purchaseOverdue = PurchaseInvoice::where('status', 'Overdue')->count();

        return view('reports.index', compact(
            'monthLabels',
            'salesData',
            'purchaseData',
            'budgetLabels',
            'budgetLimits',
            'budgetSpent',
            'salesPaid',
            'salesPending',
            'salesOverdue',
            'purchasePaid',
            'purchasePending',
            'purchaseOverdue'
        ));
    }
}
