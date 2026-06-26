@extends('layouts.app')

@section('title', 'Dashboard - Invoice & Budget System')
@section('page_title', 'Financial Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Sales Card -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card h-100 border-start border-4 border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Sales Invoiced</span>
                        <h3 class="mt-1 mb-0 font-weight-bold">${{ number_format($totalSales, 2) }}</h3>
                    </div>
                    <div class="bg-primary-light text-primary rounded-3 p-3">
                        <i class="fa-solid fa-file-invoice fs-4"></i>
                    </div>
                </div>
                <div class="text-muted" style="font-size: 13px;">
                    <span class="text-success font-weight-bold"><i class="fa-solid fa-check-double"></i> Received:</span> 
                    ${{ number_format($salesPayments, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Total Purchases Card -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card h-100 border-start border-4 border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Purchase Bills</span>
                        <h3 class="mt-1 mb-0 font-weight-bold">${{ number_format($totalPurchases, 2) }}</h3>
                    </div>
                    <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-3" style="background-color: #fef3c7; color: #d97706;">
                        <i class="fa-solid fa-receipt fs-4"></i>
                    </div>
                </div>
                <div class="text-muted" style="font-size: 13px;">
                    <span class="text-danger font-weight-bold"><i class="fa-solid fa-share"></i> Paid Out:</span> 
                    ${{ number_format($purchasePayments, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Receivables Card -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Accounts Receivable</span>
                        <h3 class="mt-1 mb-0 font-weight-bold text-success">${{ number_format($receivables, 2) }}</h3>
                    </div>
                    <div class="bg-success-subtle text-success rounded-3 p-3" style="background-color: #dcfce7; color: #15803d;">
                        <i class="fa-solid fa-circle-arrow-down fs-4"></i>
                    </div>
                </div>
                <div class="text-muted" style="font-size: 13px;">
                    <span class="text-danger font-weight-bold">{{ $overdueSalesCount }}</span> overdue sales invoices
                </div>
            </div>
        </div>
    </div>

    <!-- Payables Card -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card h-100 border-start border-4 border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Accounts Payable</span>
                        <h3 class="mt-1 mb-0 font-weight-bold text-danger">${{ number_format($payables, 2) }}</h3>
                    </div>
                    <div class="bg-danger-subtle text-danger rounded-3 p-3" style="background-color: #fee2e2; color: #b91c1c;">
                        <i class="fa-solid fa-circle-arrow-up fs-4"></i>
                    </div>
                </div>
                <div class="text-muted" style="font-size: 13px;">
                    <span class="text-danger font-weight-bold">{{ $overduePurchaseCount }}</span> overdue purchase bills
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Active Budgets & Progress -->
    <div class="col-12 col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Budget Utilization Summary</span>
                <a href="{{ route('budgets.index') }}" class="btn btn-sm btn-outline-primary">Manage Budgets</a>
            </div>
            <div class="card-body">
                @if($budgets->isEmpty())
                    <p class="text-muted text-center py-4">No active budgets found. Create one to begin tracking!</p>
                @else
                    @foreach($budgets as $budget)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-weight-bold">{{ $budget['category_name'] }}</span>
                                <span class="text-muted" style="font-size: 13px;">
                                    ${{ number_format($budget['spent'], 2) }} / ${{ number_format($budget['limit'], 2) }}
                                </span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                @php
                                    $percent = $budget['percentage'];
                                    $barClass = 'bg-primary';
                                    if ($percent >= 100) {
                                        $barClass = 'bg-danger';
                                    } elseif ($percent >= 80) {
                                        $barClass = 'bg-warning';
                                    }
                                @endphp
                                <div class="progress-bar {{ $barClass }}" role="progressbar" style="width: {{ min(100, $percent) }}%; border-radius: 5px;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1" style="font-size: 11px;">
                                @if($percent >= 100)
                                    <span class="text-danger font-weight-bold"><i class="fa-solid fa-triangle-exclamation"></i> Over budget by {{ $percent - 100 }}%</span>
                                @elseif($percent >= 80)
                                    <span class="text-warning font-weight-bold">Warning: {{ 100 - $percent }}% limit remaining</span>
                                @else
                                    <span class="text-success">{{ 100 - $percent }}% limit remaining</span>
                                @endif
                                <span class="text-muted font-weight-bold">{{ $percent }}% Used</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Exchange Rate Widget (API Data) -->
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-transparent d-flex align-items-center gap-2">
                <i class="fa-solid fa-globe text-primary"></i>
                <span>Currency Exchange Rates</span>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <span class="badge bg-light text-dark p-2 border border-secondary-subtle">Base Currency: <strong>1 USD</strong></span>
                </div>
                
                @if($apiError)
                    <div class="alert alert-warning p-2" style="font-size: 12px;">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        {{ $apiError }}
                    </div>
                @endif

                @if($exchangeRates)
                    <ul class="list-group list-group-flush">
                        @foreach($exchangeRates as $currency => $rate)
                            @if($currency !== 'USD')
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 border-0 border-bottom border-light">
                                    <span class="font-weight-bold">{{ $currency }}</span>
                                    <span class="text-secondary font-monospace">{{ is_numeric($rate) ? number_format($rate, 4) : $rate }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="text-center mt-3">
                        <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-arrows-rotate"></i> Real-time rates from Open Exchange API</small>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-ban fs-3 mb-2 d-block"></i>
                        <span>No exchange rate data loaded.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Sales Invoices -->
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Sales Invoices</span>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive border-0">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="padding: 0.75rem 1rem;">Invoice #</th>
                                <th style="padding: 0.75rem 1rem;">Customer</th>
                                <th style="padding: 0.75rem 1rem;">Total</th>
                                <th style="padding: 0.75rem 1rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($recentSales->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No sales invoices logged.</td>
                                </tr>
                            @else
                                @foreach($recentSales as $invoice)
                                    <tr>
                                        <td style="padding: 0.75rem 1rem;"><a href="{{ route('sales.show', $invoice) }}" class="text-primary font-weight-bold text-decoration-none">{{ $invoice->invoice_number }}</a></td>
                                        <td style="padding: 0.75rem 1rem;">{{ $invoice->customer->name }}</td>
                                        <td style="padding: 0.75rem 1rem; font-weight: 500;">${{ number_format($invoice->total_amount, 2) }}</td>
                                        <td style="padding: 0.75rem 1rem;">
                                            @php $status = $invoice->calculated_status; @endphp
                                            <span class="badge {{ $status === 'Paid' ? 'badge-paid' : ($status === 'Pending' ? 'badge-pending' : 'badge-overdue') }}">{{ $status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Purchase Invoices -->
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Purchase Invoices</span>
                <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive border-0">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="padding: 0.75rem 1rem;">Invoice #</th>
                                <th style="padding: 0.75rem 1rem;">Supplier</th>
                                <th style="padding: 0.75rem 1rem;">Total</th>
                                <th style="padding: 0.75rem 1rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($recentPurchases->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No purchase invoices logged.</td>
                                </tr>
                            @else
                                @foreach($recentPurchases as $invoice)
                                    <tr>
                                        <td style="padding: 0.75rem 1rem;"><a href="{{ route('purchase.show', $invoice) }}" class="text-primary font-weight-bold text-decoration-none">{{ $invoice->invoice_number }}</a></td>
                                        <td style="padding: 0.75rem 1rem;">{{ $invoice->supplier->name }}</td>
                                        <td style="padding: 0.75rem 1rem; font-weight: 500;">${{ number_format($invoice->total_amount, 2) }}</td>
                                        <td style="padding: 0.75rem 1rem;">
                                            @php $status = $invoice->calculated_status; @endphp
                                            <span class="badge {{ $status === 'Paid' ? 'badge-paid' : ($status === 'Pending' ? 'badge-pending' : 'badge-overdue') }}">{{ $status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
