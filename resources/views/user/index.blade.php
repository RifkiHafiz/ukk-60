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
                        <i class="bi bi-people-fill me-2"></i>
                        All Users
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('user.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add New User
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="usersTable">
                    <thead>
                        <tr>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">No</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">User</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Username</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Role</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Phone Number</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="p-3 align-middle text-center border-bottom border-light">{{ $loop->iteration }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('storage/img/user-default.jpg') }}" alt="Profile"
                                                class="rounded-circle object-fit-cover border border-white" width="40" height="40">
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="d-block fw-semibold text-primary">{{ $user->full_name ?? 'No Name' }}</span>
                                            <span class="d-block small text-secondary">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $user->username }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <span class="badge bg-success rounded-2 fw-semibold px-3 py-2 small">{{ $user->role }}</span>
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $user->phone_number ?? '0812-3456-7890' }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-info btn-sm text-white px-3 py-1">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm text-white px-3 py-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm px-3 py-1" onclick="confirmDelete(1)" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1 d-block mb-3 opacity-25"></i>
                                        <h4 class="text-secondary mb-2">No Users Found</h4>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="p-3">
                    {{ $users->links('pagination::bootstrap-5') }}
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
    function confirmDelete(userId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '/user/' + userId;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        deleteModal.show();
    }
</script>   
@endsection
