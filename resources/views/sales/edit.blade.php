@extends('layouts.app')

@section('title', 'Edit Sales Invoice - Invoice & Budget System')
@section('page_title', 'Edit Sales Invoice')

@section('content')
<div class="card">
    <div class="card-header bg-transparent">
        <span class="fs-5 fw-bold">Edit Sales Invoice ({{ $invoice->invoice_number }})</span>
    </div>
    <form action="{{ route('sales.update', $invoice) }}" method="POST" class="card-body">
        @csrf
        @method('PUT')
        
        <!-- General Info Section -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-3">
                <label for="invoice_number" class="form-label font-weight-bold">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control font-monospace fw-bold" value="{{ $invoice->invoice_number }}" required>
            </div>
            <div class="col-12 col-md-3">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select name="customer_id" id="customer_id" class="form-select" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ $invoice->invoice_date }}" required>
            </div>
            <div class="col-12 col-md-3">
                <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $invoice->due_date }}" required>
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
                    @foreach($invoice->items as $index => $item)
                        <tr class="item-row">
                            <td>
                                <select name="items[{{ $index }}][product_id]" class="form-select product-select" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (${{ number_format($product->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control text-end quantity-input" value="{{ $item->quantity }}" min="1" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][unit_price]" class="form-control text-end price-input" value="{{ $item->unit_price }}" step="0.01" min="0" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][tax_rate]" class="form-control text-end tax-input" value="{{ $item->tax_rate }}" step="0.01" min="0" max="100" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][discount_rate]" class="form-control text-end discount-input" value="{{ $item->discount_rate }}" step="0.01" min="0" max="100" required>
                            </td>
                            <td class="text-end fw-bold row-total">${{ number_format($item->total, 2) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" {{ count($invoice->items) === 1 ? 'disabled' : '' }}><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
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
                <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Provide notes...">{{ $invoice->notes }}</textarea>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card bg-light border-0 p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold" id="invoiceSubtotal">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span class="text-muted">Discount (-) :</span>
                        <span class="fw-bold" id="invoiceDiscount">${{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span class="text-muted">Estimated Tax (+) :</span>
                        <span class="fw-bold" id="invoiceTax">${{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    <hr class="text-secondary mt-1 mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold text-dark">Invoice Total:</span>
                        <span class="fs-5 fw-bold text-primary" id="invoiceTotal">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let rowIndex = {{ count($invoice->items) }};

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const body = document.getElementById('itemsTableBody');
        const firstRow = body.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset inputs
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
        
        const removeBtn = newRow.querySelector('.remove-row-btn');
        removeBtn.removeAttribute('disabled');
        removeBtn.addEventListener('click', function() {
            newRow.remove();
            calculateInvoiceTotals();
            toggleRemoveButtons();
        });

        bindRowEvents(newRow);
        body.appendChild(newRow);
        rowIndex++;
        calculateInvoiceTotals();
        toggleRemoveButtons();
    });

    function toggleRemoveButtons() {
        const rows = document.querySelectorAll('#itemsTableBody .item-row');
        rows.forEach(row => {
            const btn = row.querySelector('.remove-row-btn');
            if (rows.length === 1) {
                btn.setAttribute('disabled', 'true');
            } else {
                btn.removeAttribute('disabled');
            }
        });
    }

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

        const removeBtn = row.querySelector('.remove-row-btn');
        if (removeBtn && !removeBtn.disabled) {
            removeBtn.addEventListener('click', function() {
                row.remove();
                calculateInvoiceTotals();
                toggleRemoveButtons();
            });
        }
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

    // Bind initial event listeners to all loaded rows
    document.querySelectorAll('#itemsTableBody .item-row').forEach(row => {
        bindRowEvents(row);
    });
</script>
@endsection
