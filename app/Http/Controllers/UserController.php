<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use App\Models\{ActivityLog, User};

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }
        $users = User::paginate(10);
        return view('user.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.detail', compact('user'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role' => $request->role,
        ];

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user = User::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created user: ' . $user->username
        ]);

        return redirect()->route('user.index')->with(['success' => 'User created successfully!']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role' => $request->role,
        ];

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated user: ' . $user->username
        ]);

        return redirect()->route('user.index')->with(['success' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $username = $user->username;
        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted user: ' . $username
        ]);

        return redirect()->route('user.index')->with(['success' => 'User deleted successfully!']);
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ];

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated own profile'
        ]);

        return redirect()->route('dashboard')->with(['success' => 'Profile updated successfully!']);
    }
}
