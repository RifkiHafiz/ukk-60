@extends('layouts.app')
@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        margin: -20px -15px 30px -15px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0284c7, #075985);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
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
                        All Loans to Return
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
                            @if (Auth::user()->role !== 'Staff')
                                <th class="text-primary fw-semibold border-0 p-3 small text-uppercase text-center">Actions</th>
                            @endif
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
                                    @elseif($loan->status === 'borrowed')
                                        <span class="badge bg-primary rounded-pill px-3 py-2">Borrowed</span>
                                    @elseif($loan->status === 'waiting')
                                        <span class="badge bg-info rounded-pill px-3 py-2">Waiting</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Returned</span>
                                    @endif
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @if (Auth::user()->role !== 'Staff')
                                            @if(!$loan->returnItem)
                                                <a href="{{ route('returns.create', ['loan_id' => $loan->id]) }}" class="btn btn-primary btn-sm px-3 py-1">
                                                    <i class="bi bi-arrow-return-left me-1"></i> Return
                                                </a>
                                            @elseif ($loan->status === 'waiting')
                                                <a href="{{ route('returns.edit', $loan->returnItem->id) }}" class="btn btn-warning btn-sm text-white px-3 py-1" title="Edit Return">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm px-3 py-1" onclick="confirmDelete({{ $loan->returnItem->id }})" title="Delete Return">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endif
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
            @if($returns->hasPages())
                <div class="p-3">
                    {{ $returns->links('pagination::bootstrap-5') }}
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

<script>
    // Confirm delete function
    function confirmDelete(returnId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '/returns/' + returnId;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        deleteModal.show();
    }
</script>
@endsection
