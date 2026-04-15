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
                                    @php
                                        $statusMap = [
                                            'submitted'  => ['label' => 'Submitted',  'class' => 'bg-warning',   'style' => ''],
                                            'approved'   => ['label' => 'Approved',   'class' => 'bg-success',   'style' => ''],
                                            'rejected'   => ['label' => 'Rejected',   'class' => 'bg-danger',    'style' => ''],
                                            'cancelled'  => ['label' => 'Cancelled',  'class' => '',             'style' => 'background:#f97316;'],
                                            'waiting'    => ['label' => 'Waiting',    'class' => 'bg-info',      'style' => ''],
                                            'borrowed'   => ['label' => 'Borrowed',   'class' => 'bg-primary',   'style' => ''],
                                            'returned'   => ['label' => 'Returned',   'class' => 'bg-secondary', 'style' => ''],
                                        ];
                                        $s = $statusMap[$loan->status] ?? ['label' => ucfirst($loan->status), 'class' => 'bg-secondary', 'style' => ''];
                                    @endphp
                                    <div class="d-flex align-items-center gap-1 flex-nowrap">
                                        <span class="badge {{ $s['class'] }} rounded-pill px-3 py-2" style="{{ $s['style'] }}">{{ $s['label'] }}</span>
                                        @if($loan->rejected_reason && in_array($loan->status, ['rejected', 'cancelled']))
                                            @php
                                                $reasonTitle = $loan->status === 'cancelled' ? 'Cancelled Reason' : 'Rejected Reason';
                                                $reasonColor = $loan->status === 'cancelled' ? '#f97316' : '#ef4444';
                                            @endphp
                                            <button type="button"
                                                class="btn btn-link btn-sm p-0"
                                                style="color:{{ $reasonColor }}; line-height:1;"
                                                title="View reason"
                                                onclick="showReason('{{ addslashes($loan->rejected_reason) }}', '{{ $reasonTitle }}')"
                                            ><i class="bi bi-info-circle-fill fs-6"></i></button>
                                        @endif
                                    </div>
                                </td>

                                <td class="p-3 align-middle border-bottom border-light text-center">
                                    @php
                                        $isAdmin    = Auth::user()->role === 'Admin';
                                        $isStaff    = Auth::user()->role === 'Staff';
                                        $isBorrower = Auth::user()->role === 'Borrower';
                                        $isOwner    = $loan->borrower_id === Auth::id();
                                        $canCancel  = $isAdmin || ($isBorrower && $isOwner);
                                        $canResubmit = $isAdmin || ($isBorrower && $isOwner);

                                        // Collect available actions
                                        $hasActions =
                                            (!$isBorrower && in_array($loan->status, ['submitted', 'approved', 'waiting'])) ||
                                            ($canCancel && $loan->status === 'submitted') ||
                                            ($canResubmit && in_array($loan->status, ['rejected', 'cancelled'])) ||
                                            ($isAdmin && $loan->status === 'submitted');
                                    @endphp

                                    @if ($hasActions)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary rounded-3 px-3" style="width: 103px;" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i> Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" style="min-width:200px">

                                            {{-- STAFF / ADMIN: Approve --}}
                                            @if (!$isBorrower && $loan->status === 'submitted')
                                                <li>
                                                    <form action="{{ route('loans.approve', $loan->id) }}" method="POST" class="action-form">
                                                        @csrf
                                                        <button type="button" class="dropdown-item text-success"
                                                            onclick="confirmAction(this, 'Approve Loan', 'Are you sure you want to approve this loan?', 'Approve', 'btn-success')">
                                                            <i class="bi bi-check-circle me-2"></i> Approve
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            {{-- STAFF / ADMIN: Reject --}}
                                            @if (!$isBorrower && $loan->status === 'submitted')
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        onclick="openRejectModal({{ $loan->id }})">
                                                        <i class="bi bi-x-circle me-2"></i> Reject
                                                    </button>
                                                </li>
                                            @endif

                                            {{-- STAFF / ADMIN: Mark as Borrowed --}}
                                            @if (!$isBorrower && $loan->status === 'approved')
                                                <li>
                                                    <form action="{{ route('loans.borrowed', $loan->id) }}" method="POST" class="action-form">
                                                        @csrf
                                                        <button type="button" class="dropdown-item text-primary"
                                                            onclick="confirmAction(this, 'Mark as Borrowed', 'Are you sure you want to mark this loan as borrowed?', 'Mark as Borrowed', 'btn-primary')">
                                                            <i class="bi bi-box-arrow-right me-2"></i> Mark as Borrowed
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            {{-- STAFF / ADMIN: Complete --}}
                                            @if (!$isBorrower && $loan->status === 'waiting')
                                                <li>
                                                    <form action="{{ route('loans.complete', $loan->id) }}" method="POST" class="action-form">
                                                        @csrf
                                                        <button type="button" class="dropdown-item text-info"
                                                            onclick="confirmAction(this, 'Complete Loan', 'Are you sure you want to complete this loan?', 'Complete', 'btn-info')">
                                                            <i class="bi bi-check-circle-fill me-2"></i> Complete
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            {{-- Divider sebelum Cancel/Resubmit jika ada aksi di atas --}}
                                            @if (!$isBorrower && in_array($loan->status, ['submitted', 'approved', 'waiting']))
                                                @if ($canCancel && $loan->status === 'submitted')
                                                    <li><hr class="dropdown-divider"></li>
                                                @endif
                                            @endif

                                            {{-- ADMIN / BORROWER: Cancel --}}
                                            @if ($canCancel && $loan->status === 'submitted')
                                                <li>
                                                    <button type="button" class="dropdown-item" style="color: #f97316;"
                                                        onclick="openCancelModal({{ $loan->id }})">
                                                        <i class="bi bi-slash-circle me-2"></i> Cancel
                                                    </button>
                                                </li>
                                            @endif

                                            {{-- ADMIN / BORROWER: Resubmit --}}
                                            @if ($canResubmit && in_array($loan->status, ['rejected', 'cancelled']))
                                                <li>
                                                    <a href="{{ route('loans.reupdate', $loan->id) }}" class="dropdown-item text-warning">
                                                        <i class="bi bi-arrow-clockwise me-2"></i> Resubmit
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- ADMIN only: Edit & Delete (submitted) --}}
                                            @if ($isAdmin && $loan->status === 'submitted')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a href="{{ route('loans.edit', $loan->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        onclick="confirmDelete({{ $loan->id }})">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
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

{{-- ===== REJECT REASON MODAL (staff/admin) ===== --}}
<div class="modal fade" id="rejectReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                <h5 class="modal-title mb-0"><i class="bi bi-x-circle me-2"></i>Reject Loan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectReasonForm" method="POST" action="">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">Please provide a reason for rejecting this loan. The borrower will be able to see this reason.</p>
                    <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                    <textarea name="rejected_reason" id="rejectReasonText" class="form-control rounded-3" rows="4"
                        placeholder="e.g. Item is currently unavailable, insufficient documentation..."
                        required minlength="5"></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3">
                        <i class="bi bi-x-circle me-1"></i> Confirm Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== CANCEL REASON MODAL (borrower) ===== --}}
<div class="modal fade" id="cancelReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#f97316,#ea580c)">
                <h5 class="modal-title mb-0"><i class="bi bi-slash-circle me-2"></i>Cancel Loan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelReasonForm" method="POST" action="">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">Please provide a reason for cancelling this loan. Admin/Staff will be able to see this reason.</p>
                    <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                    <textarea name="rejected_reason" id="cancelReasonText" class="form-control rounded-3" rows="4"
                        placeholder="e.g. No longer needed, found another source..."
                        required minlength="5"></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn rounded-3 text-white" style="background:linear-gradient(135deg,#f97316,#ea580c)">
                        <i class="bi bi-slash-circle me-1"></i> Confirm Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== VIEW REASON MODAL – dynamic title ===== --}}
<div class="modal fade" id="viewReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header text-white rounded-top-4" id="viewReasonHeader" style="background:linear-gradient(135deg,#64748b,#475569)">
                <h5 class="modal-title mb-0" id="viewReasonTitle"><i class="bi bi-info-circle me-2"></i><span id="viewReasonTitleText">Reason</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p id="viewReasonText" class="mb-0 text-secondary" style="white-space:pre-wrap"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
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

    // Open Reject modal (staff/admin)
    function openRejectModal(loanId) {
        const form = document.getElementById('rejectReasonForm');
        form.action = '/loans/' + loanId + '/reject';
        document.getElementById('rejectReasonText').value = '';
        new bootstrap.Modal(document.getElementById('rejectReasonModal')).show();
    }

    // Open Cancel modal (borrower)
    function openCancelModal(loanId) {
        const form = document.getElementById('cancelReasonForm');
        form.action = '/loans/' + loanId + '/cancel';
        document.getElementById('cancelReasonText').value = '';
        new bootstrap.Modal(document.getElementById('cancelReasonModal')).show();
    }

    // Show reason modal with dynamic title
    function showReason(reason, title) {
        document.getElementById('viewReasonText').textContent = reason;
        document.getElementById('viewReasonTitleText').textContent = title || 'Reason';
        const isCancelled = title && title.toLowerCase().includes('cancel');
        document.getElementById('viewReasonHeader').style.background = isCancelled
            ? 'linear-gradient(135deg,#f97316,#ea580c)'
            : 'linear-gradient(135deg,#ef4444,#dc2626)';
        new bootstrap.Modal(document.getElementById('viewReasonModal')).show();
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
