@extends('layouts.app')
@section('content')
<style>
    /* Minimal custom styles */
    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0284c7, #075985);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
    }

    .item-card {
        transition: all 0.3s ease;
        height: 100%;
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.15);
    }

    .item-image {
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    }

    .search-input:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.15);
    }
</style>

<div class="bg-light min-vh-100 py-4">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <h3 class="fw-bold text-primary mb-0 fs-4">
                            <i class="bi bi-box-seam-fill me-2"></i>
                            All Items
                        </h3>
                        <p class="text-muted small mb-0 mt-1">Manage your inventory items</p>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <form method="GET" action="{{ route('items.index') }}">
                                <input type="text" class="form-control border-start-0 rounded-start-0 search-input"
                                id="searchInput" placeholder="Search items..." name="search" value="{{ request('search') }}">
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('items.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add New Item
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="d-flex gap-2 overflow-auto">
                    <a href="{{ route('items.index') }}"
                    class="btn btn-sm px-3 {{ request('category') ? 'btn-outline-primary' : 'btn-primary' }}">
                        All Items
                    </a>

                    @foreach($categories as $category)
                        <a href="{{ route('items.index', ['category' => $category->id]) }}"
                        class="btn btn-sm px-3 {{ request('category') == $category->id ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="row g-4" id="itemsGrid">
            @forelse ($items as $item)
                <div class="col-12 col-md-6 col-lg-4 item-container" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->item_name) }}">
                    <div class="card border-0 rounded-4 shadow-sm item-card">
                        <!-- Item Image -->
                        <div class="position-relative">
                            @if($item->item_image)
                                <img src="{{ asset('storage/' . $item->item_image) }}"
                                     class="card-img-top rounded-top-4 item-image"
                                     alt="{{ $item->item_name }}">
                            @else
                                <div class="item-image rounded-top-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-box-seam display-1 text-primary opacity-25"></i>
                                </div>
                            @endif

                            <!-- Condition Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                @if($item->condition === 'Good')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Good
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>Damaged
                                    </span>
                                @endif
                            </div>

                            <!-- Item Code Badge -->
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 rounded-pill px-3 py-2">
                                    {{ $item->item_code }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4">
                            <!-- Item Name -->
                            <h5 class="card-title fw-bold text-primary mb-2">
                                {{ $item->item_name }}
                            </h5>

                            <!-- Category -->
                            <p class="text-muted small mb-3">
                                <i class="bi bi-tag-fill me-1"></i>
                                {{ $item->category->category_name ?? 'No Category' }}
                            </p>

                            <!-- Quantity Info -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-2 text-center">
                                        <div class="small text-muted">Total</div>
                                        <div class="fw-bold text-dark">{{ $item->total_quantity }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-2 text-center">
                                        <div class="small text-muted">Available</div>
                                        <div class="fw-bold text-success">{{ $item->available_quantity }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                @php
                                    $percentage = $item->total_quantity > 0 ? ($item->available_quantity / $item->total_quantity) * 100 : 0;
                                @endphp
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Availability</span>
                                    <span>{{ number_format($percentage, 0) }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $percentage }}%"
                                         aria-valuenow="{{ $percentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('items.show', $item->id) }}"
                                   class="btn btn-info btn-sm text-white flex-fill">
                                    <i class="bi bi-eye me-1"></i>
                                    View
                                </a>
                                <a href="{{ route('items.edit', $item->id) }}"
                                   class="btn btn-warning btn-sm text-white flex-fill">
                                    <i class="bi bi-pencil me-1"></i>
                                    Edit
                                </a>
                                <button class="btn btn-danger btn-sm flex-fill"
                                        onclick="confirmDelete({{ $item->id }})"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted opacity-25 d-block mb-3"></i>
                            <h4 class="text-secondary mb-2">No Items Found</h4>
                            <p class="text-muted mb-4">Start adding items to your inventory</p>
                            <a href="{{ route('items.create') }}" class="btn btn-primary px-4">
                                <i class="bi bi-plus-circle me-2"></i>
                                Add Your First Item
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="p-3">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="#" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-3">
                        <i class="bi bi-trash me-2"></i>
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirm delete function
    function confirmDelete(itemId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteItemModal'));
        const form = document.getElementById('deleteForm');
        form.action = `/items/${itemId}`;
        modal.show();
    }
</script>

@endsection
