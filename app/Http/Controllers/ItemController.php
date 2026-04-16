<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ActivityLog, Category, Item, Loan};

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->paginate(9);
        $categories = Category::all();
        return view('items.index', compact('items', 'categories'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('items.detail', compact('item', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|max:50|unique:items,item_code',
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'total_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'item_image' => 'nullable|image|max:2048',
            'condition' => 'required|in:Good,Damaged',
        ], [
            'item_code.unique'=> 'Item code is already exist, please use a different code!',
        ]);

        $data = [
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'category_id' => $request->category_id,
            'total_quantity' => $request->total_quantity,
            'available_quantity' => $request->available_quantity,
            'condition' => $request->condition,
        ];

        if ($request->hasFile('item_image')) {
            $data['item_image'] = $request->file('item_image')->store('items', 'public');
        }

        $item = Item::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created item: ' . $item->item_name
        ]);

        return redirect()->route('items.index')->with(['success' => 'Item created successfully!']);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'item_code' => 'required|string|max:50|unique:items,item_code,' . $item->id,
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'total_quantity' => 'required|integer',
            'available_quantity' => 'required|integer',
            'item_image' => 'nullable|image|max:2048',
            'condition' => 'required|in:Good,Damaged',
        ], [
            'item_code.unique'=> 'Item code is already exist, please use a different code!'
        ]);

        $data = [
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'category_id' => $request->category_id,
            'total_quantity' => $request->total_quantity,
            'available_quantity' => $request->available_quantity,
            'condition' => $request->condition,
        ];

        if ($request->hasFile('item_image')) {
            $data['item_image'] = $request->file('item_image')->store('items', 'public');
        }

        $item->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated item: ' . $item->item_name
        ]);

        return redirect()->route('items.index')->with(['success' => 'Item updated successfully!']);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        $hasActiveLoan = Loan::where('item_id', $item->id)
            ->whereIn('status', ['approved', 'borrowed', 'waiting'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('items.index')->with(['error' => 'Cannot delete item because it is currently being borrowed.']);
        }

        $itemName = $item->item_name;
        $item->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted item: ' . $itemName
        ]);

        return redirect()->route('items.index')->with(['success' => 'Item deleted successfully!']);
    }
}
