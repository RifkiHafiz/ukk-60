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
                                    @elseif($loan->status === 'rejected')
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
                                                <form action="{{ route('loans.approve', $loan->id) }}" method="POST" class="d-inline action-form">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-success btn-sm w-100 px-3 py-1"
                                                        title="Approve Loan"
                                                        onclick="confirmAction(this, 'Approve Loan', 'Are you sure you want to approve this loan?', 'Approve', 'btn-success')">
                                                        <i class="bi bi-check-circle me-1"></i> Approve
                                                    </button>
                                                </form>
                                            @endif

                                            @if($loan->status === 'submitted')
                                                <form action="{{ route('loans.reject', $loan->id) }}" method="POST" class="d-inline action-form">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm w-100 px-3 py-1"
                                                        title="Reject"
                                                        onclick="confirmAction(this, 'Reject Loan', 'Are you sure you want to reject this loan?', 'Reject', 'btn-danger')">
                                                        <i class="bi bi-x-circle me-1"></i> Reject
                                                    </button>
                                                </form>
                                            @endif

                                            @if($loan->status === 'approved')
                                                <form action="{{ route('loans.borrowed', $loan->id) }}" method="POST" class="d-inline action-form">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm w-100 px-3 py-1 text-white"
                                                        title="Mark as Borrowed"
                                                        onclick="confirmAction(this, 'Mark as Borrowed', 'Are you sure you want to mark this loan as borrowed?', 'Mark as Borrowed', 'btn-primary')">
                                                        <i class="bi bi-check-circle me-1"></i> Mark as Borrowed
                                                    </button>
                                                </form>
                                            @endif

                                            @if($loan->status === 'waiting')
                                                <form action="{{ route('loans.complete', $loan->id) }}" method="POST" class="d-inline action-form">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-info btn-sm w-100 px-3 py-1 text-white"
                                                        title="Complete Loan"
                                                        onclick="confirmAction(this, 'Complete Loan', 'Are you sure you want to complete this loan?', 'Complete', 'btn-info')">
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

<!-- Action Confirmation Modal -->
<div class="modal fade" id="actionConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4" id="actionModalHeader">
                <h5 class="modal-title mb-0" id="actionModalTitle">
                    <i class="bi bi-question-circle me-2"></i>
                    <span id="actionModalTitleText"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0" id="actionModalMessage"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn rounded-3 text-white" id="actionModalConfirmBtn">
                    <i class="bi bi-check-lg me-1"></i>
                    <span id="actionModalConfirmText"></span>
                </button>
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

    // Confirm action function (Approve, Reject, Borrowed, Complete)
    function confirmAction(btn, title, message, confirmLabel, btnClass) {
        const form = btn.closest('form');

        document.getElementById('actionModalTitleText').textContent = title;
        document.getElementById('actionModalMessage').textContent = message;
        document.getElementById('actionModalConfirmText').textContent = confirmLabel;

        // Map btn-* class to bg-* class for the modal header background
        const bgClassMap = {
            'btn-success': 'bg-success',
            'btn-danger':  'bg-danger',
            'btn-primary': 'bg-primary',
            'btn-info':    'bg-info',
            'btn-warning': 'bg-warning',
        };
        const bgClass = bgClassMap[btnClass] || 'bg-secondary';

        const confirmBtn = document.getElementById('actionModalConfirmBtn');
        confirmBtn.className = 'btn rounded-3 text-white ' + btnClass;

        // Remove previous listener and add a fresh one
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
        newBtn.addEventListener('click', function () {
            form.submit();
        });

        const header = document.getElementById('actionModalHeader');
        header.className = 'modal-header text-white rounded-top-4 ' + bgClass;

        const modal = new bootstrap.Modal(document.getElementById('actionConfirmModal'));
        modal.show();
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
