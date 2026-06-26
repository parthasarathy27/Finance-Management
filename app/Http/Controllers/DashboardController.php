<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate General Financials
        $totalSales = SalesInvoice::sum('total_amount');
        $totalPurchases = PurchaseInvoice::sum('total_amount');

        // Total payments received for Sales Invoices
        $salesPayments = \App\Models\Payment::whereNotNull('sales_invoice_id')->sum('amount');
        // Total payments made for Purchase Invoices
        $purchasePayments = \App\Models\Payment::whereNotNull('purchase_invoice_id')->sum('amount');

        $receivables = max(0, $totalSales - $salesPayments);
        $payables = max(0, $totalPurchases - $purchasePayments);

        // Budget status
        $totalBudgetLimit = Budget::sum('amount');
        $totalExpenses = Expense::sum('amount');

        // Overdue Invoice Counts
        $overdueSalesCount = SalesInvoice::where('status', 'Overdue')->count();
        $overduePurchaseCount = PurchaseInvoice::where('status', 'Overdue')->count();

        // Recent Invoices
        $recentSales = SalesInvoice::with('customer')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentPurchases = PurchaseInvoice::with('supplier')->orderBy('created_at', 'desc')->limit(5)->get();

        // 2. Public API Integration (Currency Exchange Rate API)
        $exchangeRates = null;
        $apiError = null;
        try {
            $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/USD');
            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['rates'] ?? [];
                // Filter to only display a few relevant major currencies
                $exchangeRates = [
                    'USD' => 1,
                    'EUR' => $rates['EUR'] ?? 'N/A',
                    'GBP' => $rates['GBP'] ?? 'N/A',
                    'INR' => $rates['INR'] ?? 'N/A',
                    'CAD' => $rates['CAD'] ?? 'N/A',
                    'AUD' => $rates['AUD'] ?? 'N/A',
                    'JPY' => $rates['JPY'] ?? 'N/A',
                ];
            } else {
                $apiError = 'Failed to fetch rates from Exchange Rate API.';
            }
        } catch (\Exception $e) {
            $apiError = 'Exchange Rate API is currently unavailable: ' . $e->getMessage();
        }

        // 3. Budgets with their current spending
        $budgets = Budget::with('expenses')->get()->map(function ($budget) {
            return [
                'id' => $budget->id,
                'category_name' => $budget->category_name,
                'limit' => $budget->amount,
                'spent' => $budget->spent_amount,
                'remaining' => $budget->remaining_amount,
                'percentage' => $budget->utilization_percentage,
            ];
        });

        return view('dashboard', compact(
            'totalSales',
            'totalPurchases',
            'salesPayments',
            'purchasePayments',
            'receivables',
            'payables',
            'totalBudgetLimit',
            'totalExpenses',
            'overdueSalesCount',
            'overduePurchaseCount',
            'recentSales',
            'recentPurchases',
            'exchangeRates',
            'apiError',
            'budgets'
        ));
    }
}
