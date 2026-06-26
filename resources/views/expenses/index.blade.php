@extends('layouts.app')

@section('title', 'Expense Tracking')
@section('page_title', 'Expense Tracking')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">All Expenses</span>
        @if(auth()->user() && auth()->user()->isAdmin())
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
            <i class="fa-solid fa-plus me-1"></i> Log Expense
        </button>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Date</th><th>Description</th><th>Budget Category</th>
                        <th>Supplier</th><th class="text-end">Amount</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                            <td class="fw-bold">{{ $expense->description }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $expense->budget->category_name }}</span>
                                @php
                                    $pct = $expense->budget->utilization_percentage;
                                @endphp
                                @if($pct >= 100)
                                    <span class="badge bg-danger ms-1" style="font-size:9px">Over Budget</span>
                                @elseif($pct >= 80)
                                    <span class="badge bg-warning ms-1" style="font-size:9px">Near Limit</span>
                                @endif
                            </td>
                            <td>{{ $expense->supplier ? $expense->supplier->name : '<span class="text-muted">—</span>' }}</td>
                            <td class="text-end fw-bold text-danger">${{ number_format($expense->amount,2) }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary edit-expense-btn"
                                        data-bs-toggle="modal" data-bs-target="#editExpenseModal"
                                        data-id="{{ $expense->id }}"
                                        data-budget="{{ $expense->budget_id }}"
                                        data-description="{{ $expense->description }}"
                                        data-amount="{{ $expense->amount }}"
                                        data-date="{{ $expense->date }}"
                                        data-supplier="{{ $expense->supplier_id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline-block ms-1"
                                      onsubmit="return confirm('Delete this expense?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No expenses logged yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Budget Summary Cards -->
<h5 class="fw-bold mb-3">Budget Progress</h5>
<div class="row g-3">
    @foreach($budgets as $budget)
        @php
            $pct = $budget->utilization_percentage;
            $barClass = $pct >= 100 ? 'bg-danger' : ($pct >= 80 ? 'bg-warning' : 'bg-primary');
        @endphp
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-size:13px">{{ $budget->category_name }}</h6>
                    <div class="progress mb-1" style="height:8px;border-radius:4px">
                        <div class="progress-bar {{ $barClass }}" style="width:{{ min(100,$pct) }}%;border-radius:4px"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size:11px">
                        <span class="text-muted">${{ number_format($budget->spent_amount,0) }} spent</span>
                        <span class="{{ $pct>=100 ? 'text-danger fw-bold' : 'text-muted' }}">{{ number_format($pct,0) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Create Expense Modal -->
<div class="modal fade" id="createExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('expenses.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Log New Expense</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Budget Category <span class="text-danger">*</span></label>
                    <select name="budget_id" class="form-select" required>
                        <option value="" disabled selected>Select budget</option>
                        @foreach($budgets as $b)
                            <option value="{{ $b->id }}">{{ $b->category_name }} (Limit: ${{ number_format($b->amount,2) }})</option>
                        @endforeach
                    </select></div>
                <div class="mb-3"><label class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control" placeholder="e.g. Office supplies, Travel" required></div>
                <div class="mb-3"><label class="form-label">Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="0.00" required></div>
                <div class="mb-3"><label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                <div class="mb-3"><label class="form-label">Supplier (optional)</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">— No Supplier —</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Log Expense</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editExpenseForm" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Edit Expense</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Budget Category <span class="text-danger">*</span></label>
                    <select name="budget_id" id="edit_budget_id" class="form-select" required>
                        @foreach($budgets as $b)
                            <option value="{{ $b->id }}">{{ $b->category_name }}</option>
                        @endforeach
                    </select></div>
                <div class="mb-3"><label class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" id="edit_description" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="edit_amount" class="form-control" step="0.01" min="0.01" required></div>
                <div class="mb-3"><label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="edit_date" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Supplier</label>
                    <select name="supplier_id" id="edit_supplier_id" class="form-select">
                        <option value="">— No Supplier —</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.edit-expense-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('editExpenseForm').action = `{{ url('expenses') }}/${this.dataset.id}`;
        document.getElementById('edit_budget_id').value = this.dataset.budget;
        document.getElementById('edit_description').value = this.dataset.description;
        document.getElementById('edit_amount').value = this.dataset.amount;
        document.getElementById('edit_date').value = this.dataset.date;
        document.getElementById('edit_supplier_id').value = this.dataset.supplier || '';
    });
});
</script>
@endsection
