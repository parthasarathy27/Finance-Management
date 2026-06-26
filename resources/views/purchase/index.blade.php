@extends('layouts.app')

@section('title', 'Purchase Invoices - Invoice & Budget System')
@section('page_title', 'Purchase Invoices')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">All Purchase Invoices</span>
        @if(auth()->user() && auth()->user()->isAdmin())
        <a href="{{ route('purchase.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Create Purchase Invoice
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Supplier</th>
                        <th>Invoice Date</th>
                        <th>Due Date</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-end">Paid Amount</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="fw-bold">
                                <a href="{{ route('purchase.show', $invoice) }}" class="text-primary text-decoration-none">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="fw-bold">{{ $invoice->supplier->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                            <td class="text-end fw-bold text-dark">${{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="text-end text-success">${{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="text-end fw-bold text-danger">${{ number_format($invoice->outstanding_amount, 2) }}</td>
                            <td>
                                @php $status = $invoice->calculated_status; @endphp
                                <span class="badge {{ $status === 'Paid' ? 'badge-paid' : ($status === 'Pending' ? 'badge-pending' : 'badge-overdue') }}">{{ $status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('purchase.show', $invoice) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('purchase.edit', $invoice) }}" class="btn btn-sm btn-outline-secondary ms-1" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('purchase.destroy', $invoice) }}" method="POST" class="d-inline-block ms-1"
                                      onsubmit="return confirm('Delete this invoice?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No purchase invoices found. Click "Create Purchase Invoice" to add one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
