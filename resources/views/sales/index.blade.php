@extends('layouts.app')

@section('title', 'Sales Invoices - Invoice & Budget System')
@section('page_title', 'Sales Invoices')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">All Sales Invoices</span>
        @if(auth()->user() && auth()->user()->isAdmin())
        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Create Sales Invoice
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
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
                    @if($invoices->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No sales invoices found. Click "Create Sales Invoice" to add one.</td>
                        </tr>
                    @else
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="fw-bold"><a href="{{ route('sales.show', $invoice) }}" class="text-primary text-decoration-none">{{ $invoice->invoice_number }}</a></td>
                                <td class="fw-bold">{{ $invoice->customer->name }}</td>
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
                                    <a href="{{ route('sales.show', $invoice) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sales.edit', $invoice) }}" class="btn btn-sm btn-outline-secondary ms-1" title="Edit Invoice">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('sales.destroy', $invoice) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Invoice">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
