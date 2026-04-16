<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Report — BorrowMe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            padding: 20px 30px;
        }

        /* ===== HEADER ===== */
        .report-header {
            text-align: center;
            border-bottom: 3px solid #0ea5e9;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .report-header h1 {
            font-size: 18px;
            color: #0369a1;
            font-weight: bold;
        }

        .report-header p {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        /* ===== SUMMARY ===== */
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 12px 0;
        }

        .summary-box {
            display: table-cell;
            width: 50%;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }

        .summary-box .number {
            font-size: 22px;
            font-weight: bold;
            color: #0369a1;
        }

        .summary-box .label {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0369a1;
            border-left: 4px solid #0ea5e9;
            padding-left: 8px;
            margin: 20px 0 10px 0;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
        }

        thead tr {
            background-color: #e0f2fe;
        }

        th {
            padding: 7px 8px;
            text-align: left;
            font-weight: 700;
            color: #0369a1;
            text-transform: uppercase;
            font-size: 9px;
            border-bottom: 2px solid #bae6fd;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge-primary   { background: #dbeafe; color: #1d4ed8; }
        .badge-success   { background: #dcfce7; color: #15803d; }
        .badge-warning   { background: #fef9c3; color: #b45309; }
        .badge-orange    { background: #ffd2b2; color: #ce5906; }
        .badge-danger    { background: #fee2e2; color: #b91c1c; }
        .badge-info      { background: #e0f2fe; color: #0369a1; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        .empty-cell {
            text-align: center;
            color: #94a3b8;
            padding: 14px;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .report-footer {
            margin-top: 24px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="report-header">
        <h1>ACTIVITY REPORT — BorrowMe</h1>
        @if($startDate && $endDate)
            <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
               &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        @else
            <p>All Periods</p>
        @endif
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>
    </div>

    {{-- Summary --}}
    <div class="summary-row">
        <div class="summary-box">
            <div class="number">{{ $loans->count() }}</div>
            <div class="label">Total Loans</div>
        </div>
        <div class="summary-box">
            <div class="number">{{ $returns->count() }}</div>
            <div class="label">Total Returns</div>
        </div>
    </div>

    {{-- ========== LOANS TABLE ========== --}}
    <div class="section-title">Loan Data</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Loan Code</th>
                <th>Borrower</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Loan Date</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge badge-primary">{{ $loan->loan_code }}</span></td>
                    <td>{{ $loan->user->username ?? '-' }}</td>
                    <td>{{ $loan->item->item_name ?? '-' }}</td>
                    <td>{{ $loan->quantity }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</td>
                    <td>
                        @php
                            $sc = match($loan->status) {
                                'submitted' => 'warning',
                                'approved'  => 'success',
                                'borrowed'  => 'primary',
                                'waiting'   => 'info',
                                'returned'  => 'success',
                                'rejected'  => 'danger',
                                'cancelled' => 'orange',
                                default     => 'dark',
                            };
                        @endphp
                        {{-- @if ($loan->status === 'cancelled')
                            <span class="badge" style="background:#f97416d2;">{{ ucfirst('cancelled') }}</span>
                        @else --}}
                            <span class="badge badge-{{ $sc }}">{{ ucfirst($loan->status) }}</span>
                        {{-- @endif --}}
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty-cell">No loan records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ========== RETURNS TABLE ========== --}}
    <div class="section-title">Return Data</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Loan Code</th>
                <th>Borrower</th>
                <th>Item</th>
                <th>Return Date</th>
                <th>Condition</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returns as $return)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge badge-primary">{{ $return->loan->loan_code ?? '-' }}</span></td>
                    <td>{{ $return->loan->user->username ?? '-' }}</td>
                    <td>{{ $return->loan->item->item_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}</td>
                    <td>
                        @php
                            $cc = match($return->condition) {
                                'Good'    => 'success',
                                'Damaged' => 'danger',
                                default   => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $cc }}">{{ ucfirst($return->condition) }}</span>
                    </td>
                    <td>{{ $return->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">No return records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="report-footer">
        BorrowMe &mdash; Item Borrowing Management System &nbsp;|&nbsp;
        Report auto-generated on {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
    </div>

</body>
</html>
