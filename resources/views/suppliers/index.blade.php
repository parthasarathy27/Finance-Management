@extends('layouts.app')

@section('title', 'Suppliers - Invoice & Budget System')
@section('page_title', 'Suppliers Management')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">Suppliers Directory</span>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            <i class="fa-solid fa-plus me-1"></i> Add Supplier
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Tax Number</th>
                        <th>Address</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($suppliers->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No suppliers found. Click "Add Supplier" to create one.</td>
                        </tr>
                    @else
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td class="fw-bold">{{ $supplier->name }}</td>
                                <td>{{ $supplier->email ?? 'N/A' }}</td>
                                <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $supplier->tax_number ?? 'N/A' }}</span></td>
                                <td style="max-width: 250px;" class="text-truncate">{{ $supplier->address ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary edit-supplier-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editSupplierModal"
                                            data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}"
                                            data-email="{{ $supplier->email }}"
                                            data-phone="{{ $supplier->phone }}"
                                            data-tax="{{ $supplier->tax_number }}"
                                            data-address="{{ $supplier->address }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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

<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('suppliers.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Company or Supplier Name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="billing@supplier.com">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="+1-555-0100">
                </div>
                <div class="mb-3">
                    <label for="tax_number" class="form-label">Tax ID / GSTIN</label>
                    <input type="text" name="tax_number" id="tax_number" class="form-control" placeholder="TAX-SUP-9912">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Billing Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Street, City, State, ZIP"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Supplier</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editSupplierForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_tax_number" class="form-label">Tax ID / GSTIN</label>
                    <input type="text" name="tax_number" id="edit_tax_number" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_address" class="form-label">Billing Address</label>
                    <textarea name="address" id="edit_address" class="form-control" rows="3"></textarea>
                </div>
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
    document.querySelectorAll('.edit-supplier-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const tax = this.getAttribute('data-tax');
            const address = this.getAttribute('data-address');

            // Set Form action URL dynamically
            document.getElementById('editSupplierForm').action = `{{ url('suppliers') }}/${id}`;

            // Populate Modal inputs
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email || '';
            document.getElementById('edit_phone').value = phone || '';
            document.getElementById('edit_tax_number').value = tax || '';
            document.getElementById('edit_address').value = address || '';
        });
    });
</script>
@endsection
