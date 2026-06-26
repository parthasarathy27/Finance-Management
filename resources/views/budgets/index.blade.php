@extends('layouts.app')

@section('title', 'Budget Management')
@section('page_title', 'Budget Management')

@section('content')
<div class="row g-4 mb-4">
    @forelse($budgets as $budget)
        @php
            $pct = $budget->utilization_percentage;
            $barClass = $pct >= 100 ? 'bg-danger' : ($pct >= 80 ? 'bg-warning' : 'bg-primary');
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 {{ $pct >= 100 ? 'border-danger' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $budget->category_name }}</h6>
                            <span class="badge bg-light text-dark border" style="font-size:10px">{{ $budget->type }}</span>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-sm btn-outline-secondary edit-budget-btn"
                                    data-bs-toggle="modal" data-bs-target="#editBudgetModal"
                                    data-id="{{ $budget->id }}"
                                    data-category="{{ $budget->category_name }}"
                                    data-amount="{{ $budget->amount }}"
                                    data-start="{{ $budget->start_date }}"
                                    data-end="{{ $budget->end_date }}"
                                    data-type="{{ $budget->type }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="d-inline-block ms-1"
                                  onsubmit="return confirm('Delete this budget?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-1" style="font-size:13px">
                        <span class="text-muted">Spent: <strong class="text-dark">${{ number_format($budget->spent_amount,2) }}</strong></span>
                        <span class="text-muted">Limit: <strong class="text-dark">${{ number_format($budget->amount,2) }}</strong></span>
                    </div>
                    <div class="progress mb-2" style="height:10px;border-radius:5px">
                        <div class="progress-bar {{ $barClass }}" style="width:{{ min(100,$pct) }}%;border-radius:5px"></div>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:11px">
                        @if($pct >= 100)
                            <span class="text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation"></i> Over Budget!</span>
                        @elseif($pct >= 80)
                            <span class="text-warning fw-bold"><i class="fa-solid fa-circle-exclamation"></i> Near Limit</span>
                        @else
                            <span class="text-success">On Track</span>
                        @endif
                        <span class="fw-bold {{ $pct >= 100 ? 'text-danger' : '' }}">{{ number_format($pct,1) }}% used</span>
                    </div>

                    <hr class="mt-2 mb-2">
                    <div class="d-flex justify-content-between" style="font-size:12px">
                        <span class="text-muted"><i class="fa-solid fa-calendar-days me-1"></i>{{ \Carbon\Carbon::parse($budget->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($budget->end_date)->format('M d, Y') }}</span>
                        <span class="text-muted">Remaining: <strong class="{{ $budget->remaining_amount > 0 ? 'text-success' : 'text-danger' }}">${{ number_format($budget->remaining_amount,2) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="fa-solid fa-wallet fs-1 mb-3 d-block"></i>
                    <p>No budgets configured. Create your first budget to start tracking spending.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">All Budgets</span>
        @if(auth()->user() && auth()->user()->isAdmin())
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBudgetModal">
            <i class="fa-solid fa-plus me-1"></i> New Budget
        </button>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Category</th><th>Type</th><th class="text-end">Budget Limit</th>
                        <th class="text-end">Spent</th><th class="text-end">Remaining</th>
                        <th>Period</th><th>Utilization</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgets as $budget)
                        @php $pct = $budget->utilization_percentage; @endphp
                        <tr>
                            <td class="fw-bold">{{ $budget->category_name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $budget->type }}</span></td>
                            <td class="text-end fw-bold">${{ number_format($budget->amount,2) }}</td>
                            <td class="text-end text-danger">${{ number_format($budget->spent_amount,2) }}</td>
                            <td class="text-end {{ $budget->remaining_amount > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                ${{ number_format($budget->remaining_amount,2) }}
                            </td>
                            <td style="font-size:12px">{{ \Carbon\Carbon::parse($budget->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($budget->end_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:6px">
                                        <div class="progress-bar {{ $pct>=100 ? 'bg-danger' : ($pct>=80 ? 'bg-warning' : 'bg-primary') }}" style="width:{{ min(100,$pct) }}%"></div>
                                    </div>
                                    <span style="font-size:11px;min-width:40px" class="{{ $pct>=100 ? 'text-danger fw-bold' : '' }}">{{ number_format($pct,0) }}%</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary edit-budget-btn"
                                        data-bs-toggle="modal" data-bs-target="#editBudgetModal"
                                        data-id="{{ $budget->id }}"
                                        data-category="{{ $budget->category_name }}"
                                        data-amount="{{ $budget->amount }}"
                                        data-start="{{ $budget->start_date }}"
                                        data-end="{{ $budget->end_date }}"
                                        data-type="{{ $budget->type }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="d-inline-block ms-1"
                                      onsubmit="return confirm('Delete this budget?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">No budgets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createBudgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('budgets.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Create New Budget</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" class="form-control" placeholder="e.g. Marketing, Office Operations" required></div>
                <div class="mb-3"><label class="form-label">Budget Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="5000.00" required></div>
                <div class="row g-3">
                    <div class="col-6"><label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}" required></div>
                    <div class="col-6"><label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" value="{{ now()->endOfMonth()->toDateString() }}" required></div>
                </div>
                <div class="mt-3"><label class="form-label">Budget Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="Monthly" selected>Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Budget</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBudgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editBudgetForm" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Edit Budget</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="category_name" id="edit_category" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Budget Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="edit_amount" class="form-control" step="0.01" min="0.01" required></div>
                <div class="row g-3">
                    <div class="col-6"><label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="edit_start" class="form-control" required></div>
                    <div class="col-6"><label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="edit_end" class="form-control" required></div>
                </div>
                <div class="mt-3"><label class="form-label">Type</label>
                    <select name="type" id="edit_type" class="form-select" required>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
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
document.querySelectorAll('.edit-budget-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('editBudgetForm').action = `{{ url('budgets') }}/${this.dataset.id}`;
        document.getElementById('edit_category').value = this.dataset.category;
        document.getElementById('edit_amount').value = this.dataset.amount;
        document.getElementById('edit_start').value = this.dataset.start;
        document.getElementById('edit_end').value = this.dataset.end;
        document.getElementById('edit_type').value = this.dataset.type;
    });
});
</script>
@endsection
