<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Item;
use App\Models\Category;
use App\Models\ActivityLog;

class LoanController extends Controller
{
    public function index() {
        $items = Item::with('category')->paginate(9);
        $categories = Category::all();
        $loans = Loan::with('user', 'item')->paginate(9);
        return view('loans.index', compact('loans', 'items', 'categories'));
    }

    public function show() {
        $items = Item::with('category')->paginate(10);
        $categories = Category::all();

        $user = auth()->user();

        if (in_array($user->role, ['Admin', 'Staff'])) {
            $loans = Loan::with('user', 'item')->paginate(10);
        } else {
            $loans = Loan::with('user', 'item')
                ->where('borrower_id', $user->id)
                ->paginate(10);
        }

        return view('loans.index-table', compact('loans', 'items', 'categories'));
    }

    public function create(Request $request) {
        $items = Item::with('category')->get();
        $categories = Category::all();
        $loans = Loan::with('user', 'item')->get();
        $selectedItemId = $request->query('item_id');
        $selectedItem = $selectedItemId ? Item::find($selectedItemId) : null;
        return view('loans.create', compact('loans', 'items', 'categories', 'selectedItem'));
    }

    public function store(Request $request) {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        $item = Item::findOrFail($request->item_id);
        $quantity = $request->quantity;

        if ($item->available_quantity < $quantity) {
            return redirect()->back()->with(['error' => 'Quantity tidak tersedia! Available: ' . $item->available_quantity]);
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

    public function edit($id) {
        $loan = Loan::with('item.category')->findOrFail($id);
        $selectedItem = $loan->item;
        $items = Item::with('category')->get();
        $categories = Category::all();
        return view('loans.edit', compact('loan', 'items', 'categories', 'selectedItem'));
    }

    public function update(Request $request, $id) {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        $item = Item::findOrFail($request->item_id);
        $newQuantity = $request->quantity;
        $oldQuantity = $loan->quantity;
        $quantityDifference = $newQuantity - $oldQuantity;

        if ($quantityDifference > 0 && $item->available_quantity < $quantityDifference) {
            return redirect()->back()->with(['error' => 'Quantity not available! Available: ' . $item->available_quantity]);
        }

        $loan->update([
            'item_id' => $request->item_id,
            'quantity' => $newQuantity,
            'loan_date' => $request->loan_date,
            'return_date' => $request->return_date,
            'status' => 'submitted',
            'notes' => $request->notes,
        ]);

        if ($quantityDifference != 0) {
            $item->available_quantity -= $quantityDifference;
            $item->save();
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated loan: ' . $loan->loan_code
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan updated successfully!']);
    }

    public function approve($id) {
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

    public function reject($id) {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'submitted') {
            return redirect()->back()->with(['error' => 'Only submitted loans can be rejected!']);
        }

        $loan->status = 'rejected';
        $loan->staff_id = auth()->id();
        $loan->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Rejected loan: ' . $loan->loan_code
        ]);

        return redirect()->route('loans.index-table')->with(['success' => 'Loan rejected successfully!']);
    }

    public function borrowed($id) {
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

    public function complete($id) {
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

    public function destroy($id) {
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
