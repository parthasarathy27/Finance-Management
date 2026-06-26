@extends('layouts.app')

@section('title', 'Reports & Analytics')
@section('page_title', 'Reports & Analytics')

@section('content')

<!-- Summary Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card text-center border-0" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff">
            <div class="card-body py-4">
                <i class="fa-solid fa-file-invoice fs-2 mb-2 d-block opacity-75"></i>
                <h4 class="fw-bold mb-0">{{ $salesPaid + $salesPending + $salesOverdue }}</h4>
                <small class="opacity-75">Total Sales Invoices</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center border-0" style="background:linear-gradient(135deg,#0891b2,#0284c7);color:#fff">
            <div class="card-body py-4">
                <i class="fa-solid fa-receipt fs-2 mb-2 d-block opacity-75"></i>
                <h4 class="fw-bold mb-0">{{ $purchasePaid + $purchasePending + $purchaseOverdue }}</h4>
                <small class="opacity-75">Total Purchase Invoices</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center border-0" style="background:linear-gradient(135deg,#059669,#10b981);color:#fff">
            <div class="card-body py-4">
                <i class="fa-solid fa-circle-check fs-2 mb-2 d-block opacity-75"></i>
                <h4 class="fw-bold mb-0">{{ $salesPaid }}</h4>
                <small class="opacity-75">Paid Sales Invoices</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center border-0" style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff">
            <div class="card-body py-4">
                <i class="fa-solid fa-triangle-exclamation fs-2 mb-2 d-block opacity-75"></i>
                <h4 class="fw-bold mb-0">{{ $salesOverdue + $purchaseOverdue }}</h4>
                <small class="opacity-75">Overdue Invoices</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Sales vs Purchases Line Chart -->
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header bg-transparent">
                <span class="fw-bold">Monthly Sales vs Purchases (Last 6 Months)</span>
            </div>
            <div class="card-body">
                <canvas id="salesVsPurchasesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Invoice Status Donut -->
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-transparent">
                <span class="fw-bold">Sales Invoice Status</span>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <canvas id="salesStatusChart" height="200" style="max-height:200px"></canvas>
                <div class="d-flex gap-3 mt-3 flex-wrap justify-content-center" style="font-size:12px">
                    <span><span class="badge bg-success me-1">&nbsp;</span>Paid ({{ $salesPaid }})</span>
                    <span><span class="badge bg-warning me-1">&nbsp;</span>Pending ({{ $salesPending }})</span>
                    <span><span class="badge bg-danger me-1">&nbsp;</span>Overdue ({{ $salesOverdue }})</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Budget vs Actual Bar Chart -->
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header bg-transparent">
                <span class="fw-bold">Budget Limit vs Actual Spend</span>
            </div>
            <div class="card-body">
                <canvas id="budgetChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Purchase Status Donut -->
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-transparent">
                <span class="fw-bold">Purchase Invoice Status</span>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <canvas id="purchaseStatusChart" height="200" style="max-height:200px"></canvas>
                <div class="d-flex gap-3 mt-3 flex-wrap justify-content-center" style="font-size:12px">
                    <span><span class="badge bg-success me-1">&nbsp;</span>Paid ({{ $purchasePaid }})</span>
                    <span><span class="badge bg-warning me-1">&nbsp;</span>Pending ({{ $purchasePending }})</span>
                    <span><span class="badge bg-danger me-1">&nbsp;</span>Overdue ({{ $purchaseOverdue }})</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = @json($monthLabels);
const salesData = @json($salesData);
const purchaseData = @json($purchaseData);
const budgetLabels = @json($budgetLabels);
const budgetLimits = @json($budgetLimits);
const budgetSpent = @json($budgetSpent);

// 1. Sales vs Purchases Line Chart
new Chart(document.getElementById('salesVsPurchasesChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Sales',
                data: salesData,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.1)',
                borderWidth: 2.5,
                pointRadius: 5,
                fill: true,
                tension: 0.4,
            },
            {
                label: 'Purchases',
                data: purchaseData,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.1)',
                borderWidth: 2.5,
                pointRadius: 5,
                fill: true,
                tension: 0.4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});

// 2. Sales Status Donut
new Chart(document.getElementById('salesStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending', 'Overdue'],
        datasets: [{
            data: [{{ $salesPaid }}, {{ $salesPending }}, {{ $salesOverdue }}],
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// 3. Budget vs Actual Bar Chart
new Chart(document.getElementById('budgetChart'), {
    type: 'bar',
    data: {
        labels: budgetLabels,
        datasets: [
            {
                label: 'Budget Limit',
                data: budgetLimits,
                backgroundColor: 'rgba(79,70,229,0.6)',
                borderRadius: 6,
            },
            {
                label: 'Actual Spent',
                data: budgetSpent,
                backgroundColor: 'rgba(239,68,68,0.6)',
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});

// 4. Purchase Status Donut
new Chart(document.getElementById('purchaseStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending', 'Overdue'],
        datasets: [{
            data: [{{ $purchasePaid }}, {{ $purchasePending }}, {{ $purchaseOverdue }}],
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection
