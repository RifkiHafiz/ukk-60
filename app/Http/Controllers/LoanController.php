<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ActivityLog, Category, Item, Loan};

class LoanController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->latest()->paginate(9);
        $categories = Category::all();
        $loans = Loan::with('user', 'item')->latest()->paginate(9);
        return view('loans.index', compact('loans', 'items', 'categories'));
    }

    public function show()
    {
        $items = Item::with('category')->latest()->paginate(10);
        $categories = Category::all();

        $user = auth()->user();

        if (in_array($user->role, ['Admin', 'Staff'])) {
            $loans = Loan::with('user', 'item')->latest()->paginate(10);
        } else {
            $loans = Loan::with('user', 'item')
                ->where('borrower_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('loans.index-table', compact('loans', 'items', 'categories'));
    }

    public function create(Request $request)
    {
        $items = Item::with('category')->get();
        $categories = Category::all();
        $loans = Loan::with('user', 'item')->get();
        $selectedItemId = $request->query('item_id');
        $selectedItem = $selectedItemId ? Item::find($selectedItemId) : null;
        return view('loans.create', compact('loans', 'items', 'categories', 'selectedItem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        $item = Item::findOrFail($request->item_id);
        $quantity = $request->quantity;

        if ($item->available_quantity < $quantity) {
            return redirect()->back()->with(['error' => 'Quantity not available! Available: ' . $item->available_quantity]);
        }

        $lastLoan = Loan::orderBy('id', 'desc')->first();
        $nextNumber = ($lastLoan ? $lastLoan->id : 0) + 1;
        $loanCode = 'L' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        $data = [
            'loan_code'   => $loanCode,
            'borrower_id' => auth()->id(),
            'item_id'     => $request->item_id,
            'staff_id'    => null,
            'quantity'    => $quantity,
            'loan_date'   => $request->loan_date,
            'return_date' => $request->return_date,
            'status'      => 'submitted',
            'notes'       => $request->notes,
        ];


        $loan = Loan::create($data);

        $item->available_quantity -= $quantity;
        $item->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created loan: ' . $loan->loan_code . ' for item ' . $item->item_name
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan created successfully!']);
    }

    public function edit($id)
    {
        $loan         = Loan::with('item.category')->findOrFail($id);

        $selectedItem = $loan->item;
        $items        = Item::with('category')->get();
        $categories   = Category::all();
        return view('loans.edit', compact('loan', 'items', 'categories', 'selectedItem'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'item_id'     => 'required|exists:items,id',
            'quantity'    => 'required|integer|min:1',
            'loan_date'   => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        $item            = Item::findOrFail($request->item_id);
        $newQuantity     = $request->quantity;
        $oldQuantity     = $loan->quantity;
        $quantityDiff    = $newQuantity - $oldQuantity;

        if ($quantityDiff > 0 && $item->available_quantity < $quantityDiff) {
            return redirect()->back()->with(['error' => 'Quantity not available! Available: ' . $item->available_quantity]);
        }

        $isResubmit = in_array($loan->status, ['rejected', 'cancelled']);

        $loan->update([
            'item_id'         => $request->item_id,
            'quantity'        => $newQuantity,
            'loan_date'       => $request->loan_date,
            'return_date'     => $request->return_date,
            'status'          => $isResubmit ? 'submitted' : $loan->status,
            'rejected_reason' => $isResubmit ? null : $loan->rejected_reason,
            'notes'           => $request->notes,
        ]);

        if ($quantityDiff != 0) {
            $item->available_quantity -= $quantityDiff;
            $item->save();
        }

        ActivityLog::create([
            'user_id'  => auth()->id(),
            'activity' => $isResubmit
                ? 'Resubmitted loan: ' . $loan->loan_code
                : 'Updated loan: ' . $loan->loan_code,
        ]);

        $message = $isResubmit ? 'Loan resubmitted successfully!' : 'Loan updated successfully!';
        return redirect()->route('loans.index-table')->with(['success' => $message]);
    }

    public function reupdateLoan($id)
    {
        $loan         = Loan::with('item.category')->findOrFail($id);

        $selectedItem = $loan->item;
        $items        = Item::with('category')->get();
        $categories   = Category::all();
        return view('loans.reupdate', compact('loan', 'items', 'categories', 'selectedItem'));
    }

    public function resubmitLoan(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'item_id'     => 'required|exists:items,id',
            'quantity'    => 'required|integer|min:1',
            'loan_date'   => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        $item            = Item::findOrFail($request->item_id);
        $quantity = $request->quantity;

        $isResubmit = in_array($loan->status, ['rejected', 'cancelled']);

        $loan->update([
            'item_id'         => $request->item_id,
            'quantity'        => $quantity,
            'loan_date'       => $request->loan_date,
            'return_date'     => $request->return_date,
            'status'          => $isResubmit ? 'submitted' : $loan->status,
            'rejected_reason' => $isResubmit ? null : $loan->rejected_reason,
            'notes'           => $request->notes,
        ]);

        $item->available_quantity -= $quantity;
        $item->save();

        ActivityLog::create([
            'user_id'  => auth()->id(),
            'activity' => $isResubmit
                ? 'Resubmitted loan: ' . $loan->loan_code
                : 'Updated loan: ' . $loan->loan_code,
        ]);

        $message = $isResubmit ? 'Loan resubmitted successfully!' : 'Loan updated successfully!';
        return redirect()->route('loans.index-table')->with(['success' => $message]);
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'submitted') {
            return redirect()->back()->with(['error' => 'Only submitted loans can be approved!']);
        }

        $loan->status = 'approved';
        $loan->staff_id = auth()->id();
        $loan->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Approved loan: ' . $loan->loan_code
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan approved successfully!']);
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'submitted') {
            return redirect()->back()->with(['error' => 'Only submitted loans can be rejected!']);
        }

        $request = request();
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $item = $loan->item;
        $item->available_quantity += $loan->quantity;
        $item->save();

        $loan->status          = 'rejected';
        $loan->staff_id        = auth()->id();
        $loan->rejected_reason = $request->rejected_reason;
        $loan->save();

        ActivityLog::create([
            'user_id'  => auth()->id(),
            'activity' => 'Rejected loan: ' . $loan->loan_code,
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan rejected successfully!']);
    }

    public function cancel($id)
    {
        $loan    = Loan::findOrFail($id);
        $isAdmin = auth()->user()->role === 'Admin';

        if (!$isAdmin && $loan->borrower_id !== auth()->id()) {
            return redirect()->back()->with(['error' => 'Unauthorized!']);
        }

        if ($loan->status !== 'submitted') {
            return redirect()->back()->with(['error' => 'Only submitted loans can be cancelled!']);
        }

        $request = request();
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $item = $loan->item;
        $item->available_quantity += $loan->quantity;
        $item->save();

        $loan->status          = 'cancelled';
        $loan->rejected_reason = $request->rejected_reason;
        $loan->save();

        ActivityLog::create([
            'user_id'  => auth()->id(),
            'activity' => 'Cancelled loan: ' . $loan->loan_code,
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan cancelled successfully!']);
    }

    public function borrowed($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'approved') {
            return redirect()->back()->with(['error' => 'Only approved loans can be marked as borrowed!']);
        }

        $loan->status = 'borrowed';
        $loan->staff_id = auth()->id();
        $loan->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Marked loan as borrowed: ' . $loan->loan_code
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan marked as borrowed successfully!']);
    }

    public function complete($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'waiting') {
            return redirect()->back()->with(['error' => 'Only waiting loans can be completed!']);
        }

        $loan->status = 'returned';
        $loan->save();

        $item = $loan->item;
        $item->available_quantity += $loan->quantity;
        $item->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Completed loan: ' . $loan->loan_code
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan completed successfully!']);
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'submitted') {
            return redirect()->back()->with(['error' => 'Only loan with status submitted can be deleted!']);
        }

        $item = $loan->item;
        $item->available_quantity += $loan->quantity;
        $item->save();

        $loanCode = $loan->loan_code;
        $loan->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted loan: ' . $loanCode
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan deleted successfully!']);
    }
}
