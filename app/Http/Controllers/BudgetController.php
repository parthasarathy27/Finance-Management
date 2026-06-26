<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('expenses')->orderBy('start_date', 'desc')->get();
        return view('budgets.index', compact('budgets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:Monthly,Yearly',
        ]);

        Budget::create($data);

        return redirect()->route('budgets.index')->with('success', 'Budget category created successfully.');
    }

    public function update(Request $request, Budget $budget)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:Monthly,Yearly',
        ]);

        $budget->update($data);

        return redirect()->route('budgets.index')->with('success', 'Budget category updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Budget category deleted successfully.');
    }
}
