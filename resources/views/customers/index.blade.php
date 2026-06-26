@extends('layouts.app')

@section('title', 'Customers - Invoice & Budget System')
@section('page_title', 'Customers Management')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">Customers Directory</span>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
            <i class="fa-solid fa-plus me-1"></i> Add Customer
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
                    @if($customers->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No customers found. Click "Add Customer" to create one.</td>
                        </tr>
                    @else
                        @foreach($customers as $customer)
                            <tr>
                                <td class="fw-bold">{{ $customer->name }}</td>
                                <td>{{ $customer->email ?? 'N/A' }}</td>
                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $customer->tax_number ?? 'N/A' }}</span></td>
                                <td style="max-width: 250px;" class="text-truncate">{{ $customer->address ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary edit-customer-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCustomerModal"
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->name }}"
                                            data-email="{{ $customer->email }}"
                                            data-phone="{{ $customer->phone }}"
                                            data-tax="{{ $customer->tax_number }}"
                                            data-address="{{ $customer->address }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Are you sure you want to delete this customer?');">
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

<!-- Create Customer Modal -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('customers.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createCustomerModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Company or Individual Name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="billing@customer.com">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="+1-555-0199">
                </div>
                <div class="mb-3">
                    <label for="tax_number" class="form-label">Tax ID / GSTIN</label>
                    <input type="text" name="tax_number" id="tax_number" class="form-control" placeholder="TAX-US-1234">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Billing Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Street, City, State, ZIP"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Customer</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCustomerForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
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
    document.querySelectorAll('.edit-customer-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const tax = this.getAttribute('data-tax');
            const address = this.getAttribute('data-address');

            // Set Form action URL dynamically
            document.getElementById('editCustomerForm').action = `{{ url('customers') }}/${id}`;

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
