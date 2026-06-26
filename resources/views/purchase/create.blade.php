@extends('layouts.app')

@section('title', 'Create Purchase Invoice')
@section('page_title', 'Create Purchase Invoice')

@section('content')
<div class="card">
    <div class="card-header bg-transparent">
        <span class="fs-5 fw-bold">New Purchase Invoice Form</span>
    </div>
    <form action="{{ route('purchase.store') }}" method="POST" class="card-body">
        @csrf
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-3">
                <label class="form-label fw-bold">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" class="form-control font-monospace fw-bold"
                       value="INV-PUR-{{ date('Y') }}-{{ rand(1000,9999) }}" required>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Supplier <span class="text-danger">*</span></label>
                <select name="supplier_id" class="form-select" required>
                    <option value="" disabled selected>Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Due Date <span class="text-danger">*</span></label>
                <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
            </div>
        </div>

        <hr class="text-muted">
        <h6 class="mb-3 fw-bold text-secondary">Line Items</h6>

        <div class="table-responsive mb-3">
            <table class="table align-middle border rounded-3 overflow-hidden" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width:30%">Product/Service <span class="text-danger">*</span></th>
                        <th class="text-end" style="width:10%">Qty</th>
                        <th class="text-end" style="width:15%">Unit Price</th>
                        <th class="text-end" style="width:10%">Tax %</th>
                        <th class="text-end" style="width:10%">Disc %</th>
                        <th class="text-end" style="width:15%">Total</th>
                        <th class="text-center" style="width:10%">Action</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select product-select" required>
                                <option value="" disabled selected>Select item</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} (${{ number_format($p->price,2) }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[0][quantity]" class="form-control text-end quantity-input" value="1" min="1" required></td>
                        <td><input type="number" name="items[0][unit_price]" class="form-control text-end price-input" value="0.00" step="0.01" min="0" required></td>
                        <td><input type="number" name="items[0][tax_rate]" class="form-control text-end tax-input" value="10.00" step="0.01" min="0" max="100" required></td>
                        <td><input type="number" name="items[0][discount_rate]" class="form-control text-end discount-input" value="0.00" step="0.01" min="0" max="100" required></td>
                        <td class="text-end fw-bold row-total">$0.00</td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled><i class="fa-solid fa-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mb-4">
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="fa-solid fa-plus me-1"></i> Add Item Line
            </button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-7">
                <label class="form-label fw-bold">Notes</label>
                <textarea name="notes" class="form-control" rows="4" placeholder="Supplier terms, reference numbers..."></textarea>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card bg-light border-0 p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold" id="invoiceSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span class="text-muted">Discount (-):</span>
                        <span class="fw-bold" id="invoiceDiscount">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span class="text-muted">Tax (+):</span>
                        <span class="fw-bold" id="invoiceTax">$0.00</span>
                    </div>
                    <hr class="mt-1 mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">Total:</span>
                        <span class="fs-5 fw-bold text-primary" id="invoiceTotal">$0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('purchase.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
    removeBtn.addEventListener('click', () => { newRow.remove(); recalcAll(); });
    bindRowEvents(newRow);
    body.appendChild(newRow);
    rowIndex++;
});

function bindRowEvents(row) {
    row.querySelector('.product-select').addEventListener('change', function() {
        const price = this.options[this.selectedIndex].getAttribute('data-price') || 0;
        row.querySelector('.price-input').value = parseFloat(price).toFixed(2);
        calcRow(row);
    });
    row.querySelectorAll('.quantity-input, .price-input, .tax-input, .discount-input').forEach(i => {
        i.addEventListener('input', () => calcRow(row));
    });
    const rb = row.querySelector('.remove-row-btn');
    if (rb && !rb.disabled) rb.addEventListener('click', () => { row.remove(); recalcAll(); });
}

function calcRow(row) {
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const tax = parseFloat(row.querySelector('.tax-input').value) || 0;
    const disc = parseFloat(row.querySelector('.discount-input').value) || 0;
    const sub = qty * price;
    const total = sub + sub * (tax/100) - sub * (disc/100);
    row.querySelector('.row-total').textContent = `$${total.toFixed(2)}`;
    recalcAll();
}

function recalcAll() {
    let sub=0, tax=0, disc=0, tot=0;
    document.querySelectorAll('#itemsTableBody .item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value)||0;
        const price = parseFloat(row.querySelector('.price-input').value)||0;
        const t = parseFloat(row.querySelector('.tax-input').value)||0;
        const d = parseFloat(row.querySelector('.discount-input').value)||0;
        const s = qty*price;
        sub+=s; tax+=s*(t/100); disc+=s*(d/100); tot+=s+s*(t/100)-s*(d/100);
    });
    document.getElementById('invoiceSubtotal').textContent = `$${sub.toFixed(2)}`;
    document.getElementById('invoiceTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('invoiceDiscount').textContent = `$${disc.toFixed(2)}`;
    document.getElementById('invoiceTotal').textContent = `$${tot.toFixed(2)}`;
}

bindRowEvents(document.querySelector('#itemsTableBody .item-row'));
</script>
@endsection
