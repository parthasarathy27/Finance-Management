@extends('layouts.app')

@section('title', 'Purchase Invoice Detail')
@section('page_title', 'Purchase Invoice Detail')

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-body border-bottom p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1 fw-bold">Invoice: <span class="font-monospace text-primary">{{ $invoice->invoice_number }}</span></h4>
                        <span class="text-muted">Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="text-end">
                        @php $status = $invoice->calculated_status; @endphp
                        <span class="fs-6 badge {{ $status === 'Paid' ? 'badge-paid' : ($status === 'Pending' ? 'badge-pending' : 'badge-overdue') }}">{{ $status }}</span>
                        <div class="mt-2 text-danger fw-bold">Due: {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="card-body border-bottom bg-light p-4">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <small class="text-muted text-uppercase d-block mb-2 fw-bold" style="font-size:11px;letter-spacing:.5px">Supplier</small>
                        <h6 class="fw-bold">{{ $invoice->supplier->name }}</h6>
                        @if($invoice->supplier->email)<div class="text-muted" style="font-size:13px"><i class="fa-solid fa-envelope me-1"></i>{{ $invoice->supplier->email }}</div>@endif
                        @if($invoice->supplier->phone)<div class="text-muted" style="font-size:13px"><i class="fa-solid fa-phone me-1"></i>{{ $invoice->supplier->phone }}</div>@endif
                        @if($invoice->supplier->tax_number)<div class="mt-1"><span class="badge bg-white text-dark border" style="font-size:10px">Tax: {{ $invoice->supplier->tax_number }}</span></div>@endif
                    </div>
                    <div class="col-12 col-md-6">
                        <small class="text-muted text-uppercase d-block mb-2 fw-bold" style="font-size:11px;letter-spacing:.5px">Billed To (Our Company)</small>
                        <h6 class="fw-bold">FinTrack Admin Portal</h6>
                        <div class="text-muted" style="font-size:13px"><i class="fa-solid fa-envelope me-1"></i>admin@fintrack.example.com</div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive border-0">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="padding:.75rem 1.5rem">Product/Service</th>
                                <th class="text-end" style="padding:.75rem 1.5rem">Qty</th>
                                <th class="text-end" style="padding:.75rem 1.5rem">Unit Price</th>
                                <th class="text-end" style="padding:.75rem 1.5rem">Tax</th>
                                <th class="text-end" style="padding:.75rem 1.5rem">Discount</th>
                                <th class="text-end" style="padding:.75rem 1.5rem">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td style="padding:1rem 1.5rem">
                                    <div class="fw-bold">{{ $item->product->name }}</div>
                                    @if($item->product->sku)<small class="text-muted font-monospace" style="font-size:11px">SKU: {{ $item->product->sku }}</small>@endif
                                </td>
                                <td class="text-end" style="padding:1rem 1.5rem">{{ $item->quantity }}</td>
                                <td class="text-end" style="padding:1rem 1.5rem">${{ number_format($item->unit_price,2) }}</td>
                                <td class="text-end text-success" style="padding:1rem 1.5rem">{{ $item->tax_rate }}%</td>
                                <td class="text-end text-danger" style="padding:1rem 1.5rem">{{ $item->discount_rate }}%</td>
                                <td class="text-end fw-bold" style="padding:1rem 1.5rem">${{ number_format($item->total,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-body border-top p-4">
                <div class="row justify-content-end">
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal:</span><span class="fw-bold">${{ number_format($invoice->subtotal,2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2 text-danger"><span class="text-muted">Discount (-):</span><span class="fw-bold">${{ number_format($invoice->discount_amount,2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2 text-success"><span class="text-muted">Tax (+):</span><span class="fw-bold">${{ number_format($invoice->tax_amount,2) }}</span></div>
                        <hr class="mt-1 mb-2">
                        <div class="d-flex justify-content-between"><span class="fs-5 fw-bold">Total:</span><span class="fs-5 fw-bold text-primary">${{ number_format($invoice->total_amount,2) }}</span></div>
                    </div>
                </div>
                @if($invoice->notes)<div class="mt-3 bg-light p-3 rounded-3" style="font-size:13px"><strong>Notes:</strong> {{ $invoice->notes }}</div>@endif
            </div>

            <div class="card-footer bg-transparent d-flex justify-content-between p-3">
                <a href="{{ route('purchase.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Back</a>
                <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <span>Payment Ledger</span>
                @if($invoice->outstanding_amount > 0)
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="fa-solid fa-plus me-1"></i> Log Payment
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="bg-light p-3 rounded-3 text-center mb-4">
                    <span class="text-muted text-uppercase d-block mb-1 fw-bold" style="font-size:10px">Outstanding Balance</span>
                    <h2 class="text-danger fw-bold mb-0">${{ number_format($invoice->outstanding_amount,2) }}</h2>
                    <small class="text-success fw-bold mt-1 d-block"><i class="fa-solid fa-circle-check"></i> Paid: ${{ number_format($invoice->paid_amount,2) }}</small>
                </div>
                <h6 class="fw-bold mb-3 text-secondary">Transaction History</h6>
                @forelse($invoice->payments as $payment)
                    <div class="border-bottom border-light py-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold text-success">+ ${{ number_format($payment->amount,2) }}</span>
                            <span class="text-muted font-monospace" style="font-size:11px">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size:12px">
                            <span class="text-muted"><i class="fa-solid fa-wallet me-1"></i>{{ $payment->payment_method }}</span>
                            @if($payment->reference_number)<span class="text-secondary font-monospace">Ref: {{ $payment->reference_number }}</span>@endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted"><i class="fa-solid fa-wallet fs-3 mb-2 d-block"></i>No payments yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($invoice->outstanding_amount > 0)
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('purchase.payment.store', $invoice) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Log Payment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3 bg-light p-3 rounded-3 text-center">
                    <span class="text-muted d-block" style="font-size:13px">Outstanding Amount</span>
                    <h4 class="text-danger fw-bold mb-0">${{ number_format($invoice->outstanding_amount,2) }}</h4>
                </div>
                <div class="mb-3"><label class="form-label">Amount ($) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" max="{{ $invoice->outstanding_amount }}" value="{{ $invoice->outstanding_amount }}" required></div>
                <div class="mb-3"><label class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                <div class="mb-3"><label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="Bank Transfer" selected>Bank Transfer</option>
                        <option value="Cash">Cash</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Check">Check</option>
                    </select></div>
                <div class="mb-3"><label class="form-label">Reference Number</label>
                    <input type="text" name="reference_number" class="form-control" placeholder="TXN-12345678"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Record Payment</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
