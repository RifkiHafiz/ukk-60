@extends('layouts.app')
@section('title', 'Activity Report')
@section('content')
<style>
    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0284c7, #075985);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #16a34a, #15803d);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(34, 197, 94, 0.3);
    }

    .card {
        box-shadow: 0 2px 20px rgba(14, 165, 233, 0.1);
    }

    .table thead {
        background-color: #e0f2fe;
    }

    .stat-card {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        color: white;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
    }

    .section-title {
        color: #0369a1;
        font-weight: 700;
        border-left: 4px solid #0ea5e9;
        padding-left: 0.75rem;
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid mt-3">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mt-2" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mt-2" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header Card --}}
    <div class="card border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-bottom border-2 border-light rounded-top-4 p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="card-title fw-bold text-primary mb-0 fs-4">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>
                        Activity Report
                    </h3>
                    <p class="text-muted mb-0 mt-1 small">Loans & returns report by date range</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    @if($startDate && $endDate)
                        <a href="{{ route('reports.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                           class="btn btn-success rounded-3">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Filter Form --}}
        <div class="card-body p-4 border-bottom border-light">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-primary small text-uppercase">From Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="form-control rounded-3 border-2">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-primary small text-uppercase">To Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="form-control rounded-3 border-2">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-4">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary rounded-3 px-3">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        @if($startDate && $endDate)
        <div class="card-body p-4 border-bottom border-light">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="stat-card">
                        <i class="bi bi-bag-plus display-6 mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $loans->count() }}</h4>
                        <p class="mb-0 opacity-75 small">Total Loans</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <i class="bi bi-arrow-left-circle display-6 mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $returns->count() }}</h4>
                        <p class="mb-0 opacity-75 small">Total Returns</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card-body p-4">

            {{-- Period info --}}
            @if($startDate && $endDate)
                <p class="text-muted mb-4">
                    <i class="bi bi-calendar-range me-1"></i>
                    Showing data from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong>
                    to <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                </p>
            @else
                <p class="text-muted mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Select a date range to filter the report data. The <strong>Download PDF</strong> button will appear after filtering.
                </p>
            @endif

            {{-- ===== LOANS TABLE ===== --}}
            <h5 class="section-title">
                <i class="bi bi-bag-plus me-1"></i> Loan Data
            </h5>
            <div class="table-responsive mb-5">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">No</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Loan Code</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Borrower</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Item</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Qty</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Loan Date</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Due Date</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loop->iteration }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <span class="badge bg-primary rounded-pill">{{ $loan->loan_code }}</span>
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loan->user->username ?? '-' }}</td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loan->item->item_name ?? '-' }}</td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loan->quantity }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    @php
                                        $statusClass = match($loan->status) {
                                            'submitted' => 'warning',
                                            'approved'  => 'success',
                                            'borrowed'  => 'primary',
                                            'waiting'   => 'info',
                                            'returned'  => 'success',
                                            'rejected'  => 'danger',
                                            default     => 'dark',
                                        };
                                    @endphp
                                    @if($loan->status === 'cancelled')
                                        <span class="badge rounded-pill text-capitalize text-white"
                                            style="background:#f97316;">cancelled</span>
                                    @else
                                        <span class="badge bg-{{ $statusClass }} rounded-pill text-capitalize">
                                            {{ $loan->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                                    No loan data found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===== RETURNS TABLE ===== --}}
            <h5 class="section-title">
                <i class="bi bi-arrow-left-circle me-1"></i> Return Data
            </h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">No</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Loan Code</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Borrower</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Item</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Return Date</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Condition</th>
                            <th class="text-primary fw-semibold border-0 p-3 small text-uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td class="p-3 align-middle border-bottom border-light">{{ $loop->iteration }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    <span class="badge bg-primary rounded-pill">{{ $return->loan->loan_code ?? '-' }}</span>
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $return->loan->user->username ?? '-' }}</td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $return->loan->item->item_name ?? '-' }}</td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    {{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">
                                    @php
                                        $condClass = match($return->condition) {
                                            'Good'    => 'success',
                                            'Damaged' => 'danger',
                                            default   => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $condClass }} rounded-pill text-capitalize">
                                        {{ $return->condition }}
                                    </span>
                                </td>
                                <td class="p-3 align-middle border-bottom border-light">{{ $return->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                                    No return data found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
