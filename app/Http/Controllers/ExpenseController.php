<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Budget;
use App\Models\Supplier;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('budget', 'supplier')->orderBy('date', 'desc')->get();
        $budgets = Budget::orderBy('category_name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('expenses.index', compact('expenses', 'budgets', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Expense::create($data);

        // We can check if the budget has been exceeded and pass a warning
        $budget = Budget::find($request->budget_id);
        if ($budget->utilization_percentage > 100) {
            return redirect()->route('expenses.index')->with('warning', 'Expense logged successfully! Note: Budget "' . $budget->category_name . '" has been exceeded by ' . ($budget->utilization_percentage - 100) . '%.');
        }

        return redirect()->route('expenses.index')->with('success', 'Expense logged successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $expense->update($data);

        $budget = Budget::find($request->budget_id);
        if ($budget->utilization_percentage > 100) {
            return redirect()->route('expenses.index')->with('warning', 'Expense updated successfully! Note: Budget "' . $budget->category_name . '" is exceeded by ' . ($budget->utilization_percentage - 100) . '%.');
        }

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
