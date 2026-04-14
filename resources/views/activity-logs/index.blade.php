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

    .card {
        box-shadow: 0 2px 20px rgba(14, 165, 233, 0.1);
    }

    .table thead {
        background-color: #e0f2fe;
    }
</style>

<div class="container-fluid mt-3">
    <!-- Main Card -->
    <div class="card border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-bottom border-2 border-light rounded-top-4 p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="card-title fw-bold text-primary mb-0 fs-4">
                        <i class="bi bi-activity me-2"></i>
                        Activity Logs
                    </h3>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="activityLogsTable">
                    <thead>
                        <tr>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">No</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">User</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Activity</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activityLogs as $log)
                            <tr>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loop->iteration }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 35px; height: 35px;">
                                            <img src="{{ $log->user->profile_picture ? asset('storage/' . $log->user->profile_picture) : asset('img/user-default.jpg') }}" alt="Profile"
                                            class="rounded-circle object-fit-cover border border-white" width="35" height="35">
                                        </div>
                                        <span>{{ $log->user->username ?? 'Unknown User' }}</span>
                                    </div>
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $log->activity }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-1 d-block mb-3 opacity-25"></i>
                                        <h4 class="text-secondary mb-2">No Activity Logs Found</h4>
                                        <p class="text-muted">There are no activities recorded yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($activityLogs->hasPages())
                <div class="p-3">
                    {{ $activityLogs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
