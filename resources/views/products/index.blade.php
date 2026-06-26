@extends('layouts.app')

@section('title', 'Products - Invoice & Budget System')
@section('page_title', 'Products & Services')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5">Products & Services Directory</span>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="fa-solid fa-plus me-1"></i> Add Product/Service
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Standard Price</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No products or services found. Click "Add Product/Service" to create one.</td>
                        </tr>
                    @else
                        @foreach($products as $product)
                            <tr>
                                <td><span class="badge bg-light text-dark border font-monospace">{{ $product->sku ?? 'N/A' }}</span></td>
                                <td class="fw-bold text-dark">{{ $product->name }}</td>
                                <td class="fw-bold">${{ number_format($product->price, 2) }}</td>
                                <td style="max-width: 300px;" class="text-truncate text-muted">{{ $product->description ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary edit-product-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editProductModal"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}"
                                            data-price="{{ $product->price }}"
                                            data-description="{{ $product->description }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Are you sure you want to delete this item?');">
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

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('products.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Add Product or Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Consulting Hour, Server Hosting" required>
                </div>
                <div class="mb-3">
                    <label for="sku" class="form-label">SKU / Code</label>
                    <input type="text" name="sku" id="sku" class="form-control" placeholder="e.g. SRV-CONS-01">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Standard Price ($) <span class="text-danger">*</span></label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Provide a details of product or service..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editProductForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product/Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_sku" class="form-label">SKU / Code</label>
                    <input type="text" name="sku" id="edit_sku" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_price" class="form-label">Standard Price ($) <span class="text-danger">*</span></label>
                    <input type="number" name="price" id="edit_price" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="edit_description" class="form-label">Description</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
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
    document.querySelectorAll('.edit-product-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const sku = this.getAttribute('data-sku');
            const price = this.getAttribute('data-price');
            const description = this.getAttribute('data-description');

            // Set Form action URL dynamically
            document.getElementById('editProductForm').action = `{{ url('products') }}/${id}`;

            // Populate Modal inputs
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_sku').value = sku || '';
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description || '';
        });
    });
</script>
@endsection
