<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnItem;
use App\Models\Loan;
use App\Models\Item;
use App\Models\ActivityLog;

class ReturnController extends Controller
{
    public function index() {
        $user = auth()->user();

        $returns = ReturnItem::with('loan.item', 'loan.user')->paginate(10);

        if (in_array($user->role, ['Admin', 'Staff'])) {
            $loans = Loan::whereIn('status', ['borrowed', 'waiting', 'returned'])
                ->with('item', 'user', 'returnItem')
                ->get();
        } else {
            $loans = Loan::whereIn('status', ['borrowed', 'waiting', 'returned'])
                ->where('borrower_id', $user->id)
                ->with('item', 'user', 'returnItem')
                ->get();
        }

        return view('returns.index', compact('returns', 'loans'));
    }

    public function create(Request $request) {
        $loans = Loan::where('status', 'submitted')->with('item', 'user')->get();
        $selectedLoanId = $request->query('loan_id');
        $selectedLoan = $selectedLoanId ? Loan::with('item', 'user')->find($selectedLoanId) : null;
        return view('returns.create', compact('loans', 'selectedLoan'));
    }

    public function store(Request $request) {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:good,damaged,lost',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        if ($loan->status !== 'borrowed') {
            return redirect()->back()->with(['error' => 'Loan status must be borrowed to return!']);
        }

        $returnData = [
            'loan_id' => $request->loan_id,
            'staff_id' => auth()->id(),
            'return_date' => $request->return_date,
            'condition' => $request->condition,
            'notes' => $request->notes,
        ];

        $returnItem = ReturnItem::create($returnData);

        $item = $loan->item;
        $item->available_quantity += $loan->quantity;
        $item->save();

        $loan->status = 'waiting';
        $loan->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created return for loan: ' . $loan->loan_code
        ]);

        return redirect()->route('returns.index')->with(['success' => 'Item returned successfully!']);
    }

    public function edit($id) {
        $returnItem = ReturnItem::with('loan.item', 'loan.user')->findOrFail($id);
        $selectedLoan = $returnItem->loan;
        return view('returns.edit', compact('returnItem', 'selectedLoan'));
    }

    public function update(Request $request, $id) {
        $returnItem = ReturnItem::findOrFail($id);
        $loan = $returnItem->loan;

        $request->validate([
            'return_date' => 'required|date',
            'condition' => 'required|in:good,damaged,lost',
        ]);

        $returnItem->update([
            'return_date' => $request->return_date,
            'condition' => $request->condition,
            'notes' => $request->notes,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated return for loan: ' . $loan->loan_code
        ]);

        return redirect()->route('returns.index')->with(['success' => 'Return item updated successfully!']);
    }

    public function destroy($id) {
        $returnItem = ReturnItem::findOrFail($id);
        $loan = $returnItem->loan;
        $item = $loan->item;

        $item->available_quantity -= $loan->quantity;
        $item->save();

        $loan->status = 'borrowed';
        $loan->save();

        $loanCode = $loan->loan_code;
        $returnItem->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted return for loan: ' . $loanCode
        ]);

        return redirect()->route('returns.index')->with(['success' => 'Return item deleted successfully!']);
    }
}
