<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Loan, ReturnItem};
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $loansQuery   = Loan::with('user', 'item');
        $returnsQuery = ReturnItem::with('loan.user', 'loan.item');

        if ($startDate && $endDate) {
            $loansQuery->whereBetween('loan_date', [$startDate, $endDate]);
            $returnsQuery->whereBetween('return_date', [$startDate, $endDate]);
        }

        $loans   = $loansQuery->orderBy('loan_date', 'desc')->get();
        $returns = $returnsQuery->orderBy('return_date', 'desc')->get();

        return view('reports.index', compact('loans', 'returns', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $loansQuery   = Loan::with('user', 'item');
        $returnsQuery = ReturnItem::with('loan.user', 'loan.item');

        if ($startDate && $endDate) {
            $loansQuery->whereBetween('loan_date', [$startDate, $endDate]);
            $returnsQuery->whereBetween('return_date', [$startDate, $endDate]);
        }

        $loans   = $loansQuery->orderBy('loan_date', 'desc')->get();
        $returns = $returnsQuery->orderBy('return_date', 'desc')->get();

        $pdf = Pdf::loadView('reports.pdf', compact('loans', 'returns', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        $filename = 'Activity-Report';
        if ($startDate && $endDate) {
            $filename .= '_' . $startDate . '_to_' . $endDate;
        }

        return $pdf->download($filename . '.pdf');
    }
}
