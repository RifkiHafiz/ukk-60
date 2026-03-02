@extends('layouts.app')
@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        margin: -20px -15px 30px -15px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
    }

    .modal-header.bg-gradient-primary {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
    }

    .card {
        box-shadow: 0 2px 20px rgba(14, 165, 233, 0.1);
    }

    .table thead {
        background-color: #e0f2fe;
    }

    .search-box input:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .form-control:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
</style>

<div class="container-fluid mt-3">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mt-2" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mt-2" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Main Card -->
    <div class="card border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-bottom border-2 border-light rounded-top-4 p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="card-title fw-bold text-primary mb-0 fs-4">
                        <i class="bi bi-bag-plus-fill me-2"></i>
                        All Loans
                    </h3>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="categoriesTable">
                    <thead>
                        <tr>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">No</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Code</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Item</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Quantity</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Deadline</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Status</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                            <tr>
                                <td class="p-3 align-middle text-center border-bottom border-light">{{ $loop->iteration }}</td>

                                <td class="p-3 align-middle border-bottom border-light">{{ $loan->loan_code }}</td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loan->item->item_name }}</td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loan->quantity }}</td>
                                <td class="p-3 align-middle border-bottom border-light">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }} - <br> {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    @if($loan->status === 'submitted')
                                        <span class="badge bg-warning rounded-pill px-3 py-2">Submitted</span>
                                    @elseif($loan->status === 'approved')
                                        <span class="badge bg-success rounded-pill px-3 py-2">Approved</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Rejected</span>
                                    @elseif($loan->status === 'waiting')
                                        <span class="badge bg-info rounded-pill px-3 py-2">Waiting</span>
                                    @elseif($loan->status === 'borrowed')
                                        <span class="badge bg-primary rounded-pill px-3 py-2">Borrowed</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Returned</span>
                                    @endif
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <div class="d-flex flex-column gap-2">
                                        @if (Auth::user()->role !== 'Borrower')
                                            @if($loan->status === 'submitted')
                                                <form action="{{ route('loans.approve', $loan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100 px-3 py-1" title="Approve Loan">
                                                        <i class="bi bi-check-circle me-1"></i> Approve
                                                    </button>
                                                </form>
                                            @endif

                                            @if($loan->status === 'submitted')
                                                <form action="{{ route('loans.reject', $loan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm w-100 px-3 py-1" title="Reject">
                                                        <i class="bi bi-x-circle me-1"></i> Reject
                                                    </button>
                                                </form>
                                            @endif

                                            @if($loan->status === 'approved')
                                                <form action="{{ route('loans.borrowed', $loan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm w-100 px-3 py-1 text-white" title="Mark as Borrowed">
                                                        <i class="bi bi-check-circle me-1"></i> Mark as Borrowed
                                                    </button>
                                                </form>
                                            @endif

                                            @if($loan->status === 'waiting')
                                                <form action="{{ route('loans.complete', $loan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm w-100 px-3 py-1 text-white" title="Complete Loan">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Complete
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        <div class="d-flex gap-2">
                                            @if ($loan->status === 'submitted')
                                                @if (Auth::user()->role !== 'Staff')
                                                    <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-warning btn-sm text-white px-3 py-1 flex-fill">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm px-3 py-1 flex-fill" onclick="confirmDelete({{ $loan->id }})" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1 d-block mb-3 opacity-25"></i>
                                        <h4 class="text-secondary mb-2">No Loans Found</h4>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($loans->hasPages())
                <div class="p-3">
                    {{ $loans->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Are you sure you want to delete this user? This action cannot be undone.</p>
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
    // Open borrow modal with item details
    function openBorrowModal(itemId, itemName, availableQty, itemCode) {
        // Set item details in modal
        document.getElementById('itemId').value = itemId;
        document.getElementById('modalItemName').textContent = itemName;
        document.getElementById('modalItemCode').textContent = itemCode;
        document.getElementById('modalAvailableQty').textContent = availableQty;

        // Set max quantity
        document.getElementById('quantity').max = availableQty;

        // Set minimum return date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('return_date').min = tomorrow.toISOString().split('T')[0];

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('borrowModal'));
        modal.show();
    }

    // Confirm delete function
    function confirmDelete(loanId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '/loans/' + loanId;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        deleteModal.show();
    }

    // Validate quantity on input
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');

        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                const max = parseInt(this.max);
                const value = parseInt(this.value);

                if (value > max) {
                    this.value = max;
                }
                if (value < 1) {
                    this.value = 1;
                }
            });
        }
    });
</script>
@endsection
