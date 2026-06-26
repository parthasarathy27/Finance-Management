@extends('layouts.app')

@section('title', 'Create Sales Invoice - Invoice & Budget System')
@section('page_title', 'Create Sales Invoice')

@section('content')
<div class="card">
    <div class="card-header bg-transparent">
        <span class="fs-5 fw-bold">New Sales Invoice Form</span>
    </div>
    <form action="{{ route('sales.store') }}" method="POST" class="card-body">
        @csrf
        
        <!-- General Info Section -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-3">
                <label for="invoice_number" class="form-label font-weight-bold">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control font-monospace fw-bold" value="INV-SAL-{{ date('Y') }}-{{ rand(1000, 9999) }}" required>
            </div>
            <div class="col-12 col-md-3">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select name="customer_id" id="customer_id" class="form-select" required>
                    <option value="" selected disabled>Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-12 col-md-3">
                <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
            </div>
        </div>

        <hr class="text-muted">

        <!-- Invoice Line Items Table -->
        <h6 class="mb-3 fw-bold text-secondary">Line Items</h6>
        <div class="table-responsive mb-3 border-0">
            <table class="table align-middle border border-light-subtle rounded-3 overflow-hidden" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30%">Product / Service <span class="text-danger">*</span></th>
                        <th style="width: 12%" class="text-end">Qty <span class="text-danger">*</span></th>
                        <th style="width: 15%" class="text-end">Unit Price ($) <span class="text-danger">*</span></th>
                        <th style="width: 10%" class="text-end">Tax % <span class="text-danger">*</span></th>
                        <th style="width: 10%" class="text-end">Disc % <span class="text-danger">*</span></th>
                        <th style="width: 15%" class="text-end">Total ($)</th>
                        <th style="width: 8%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select product-select" required>
                                <option value="" selected disabled>Select Product/Service</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (${{ number_format($product->price, 2) }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control text-end quantity-input" value="1" min="1" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][unit_price]" class="form-control text-end price-input" value="0.00" step="0.01" min="0" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][tax_rate]" class="form-control text-end tax-input" value="10.00" step="0.01" min="0" max="100" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][discount_rate]" class="form-control text-end discount-input" value="0.00" step="0.01" min="0" max="100" required>
                        </td>
                        <td class="text-end fw-bold row-total">$0.00</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-4">
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="fa-solid fa-plus me-1"></i> Add Item Line
            </button>
        </div>

        <!-- Calculations & Notes -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-7">
                <label for="notes" class="form-label font-weight-bold">Invoice Notes / Terms</label>
                <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Provide payment instructions, terms of service, bank details, etc..."></textarea>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card bg-light border-0 p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold" id="invoiceSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span class="text-muted">Discount (-) :</span>
                        <span class="fw-bold" id="invoiceDiscount">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span class="text-muted">Estimated Tax (+) :</span>
                        <span class="fw-bold" id="invoiceTax">$0.00</span>
                    </div>
                    <hr class="text-secondary mt-1 mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold text-dark">Invoice Total:</span>
                        <span class="fs-5 fw-bold text-primary" id="invoiceTotal">$0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Invoice</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let rowIndex = 1;

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const body = document.getElementById('itemsTableBody');
        const firstRow = body.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset and rename fields
        newRow.querySelector('.product-select').name = `items[${rowIndex}][product_id]`;
        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.quantity-input').name = `items[${rowIndex}][quantity]`;
        newRow.querySelector('.quantity-input').value = '1';
        newRow.querySelector('.price-input').name = `items[${rowIndex}][unit_price]`;
        newRow.querySelector('.price-input').value = '0.00';
        newRow.querySelector('.tax-input').name = `items[${rowIndex}][tax_rate]`;
        newRow.querySelector('.tax-input').value = '10.00';
        newRow.querySelector('.discount-input').name = `items[${rowIndex}][discount_rate]`;
        newRow.querySelector('.discount-input').value = '0.00';
        newRow.querySelector('.row-total').textContent = '$0.00';
        
        // Enable remove button
        const removeBtn = newRow.querySelector('.remove-row-btn');
        removeBtn.removeAttribute('disabled');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
            calculateInvoiceTotals();
        });

        // Add event listeners for dynamic recalculations
        bindRowEvents(newRow);
        
        body.appendChild(newRow);
        rowIndex++;
        calculateInvoiceTotals();
    });

    function bindRowEvents(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.price-input');
        const taxInput = row.querySelector('.tax-input');
        const discountInput = row.querySelector('.discount-input');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            priceInput.value = parseFloat(price).toFixed(2);
            calculateRowTotal(row);
        });

        [quantityInput, priceInput, taxInput, discountInput].forEach(input => {
            input.addEventListener('input', function() {
                calculateRowTotal(row);
            });
        });
    }

    function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const taxRate = parseFloat(row.querySelector('.tax-input').value) || 0;
        const discountRate = parseFloat(row.querySelector('.discount-input').value) || 0;

        const subtotal = qty * price;
        const tax = subtotal * (taxRate / 100);
        const discount = subtotal * (discountRate / 100);
        const total = subtotal + tax - discount;

        row.querySelector('.row-total').textContent = `$${total.toFixed(2)}`;
        calculateInvoiceTotals();
    }

    function calculateInvoiceTotals() {
        let subtotalAccum = 0;
        let taxAccum = 0;
        let discountAccum = 0;
        let totalAccum = 0;

        document.querySelectorAll('#itemsTableBody .item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const taxRate = parseFloat(row.querySelector('.tax-input').value) || 0;
            const discountRate = parseFloat(row.querySelector('.discount-input').value) || 0;

            const sub = qty * price;
            const tax = sub * (taxRate / 100);
            const disc = sub * (discountRate / 100);
            const tot = sub + tax - disc;

            subtotalAccum += sub;
            taxAccum += tax;
            discountAccum += disc;
            totalAccum += tot;
        });

        document.getElementById('invoiceSubtotal').textContent = `$${subtotalAccum.toFixed(2)}`;
        document.getElementById('invoiceTax').textContent = `$${taxAccum.toFixed(2)}`;
        document.getElementById('invoiceDiscount').textContent = `$${discountAccum.toFixed(2)}`;
        document.getElementById('invoiceTotal').textContent = `$${totalAccum.toFixed(2)}`;
    }

    // Bind event listeners to the default initial row
    bindRowEvents(document.querySelector('#itemsTableBody .item-row'));
</script>
@endsection
