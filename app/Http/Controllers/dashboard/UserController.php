<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('dashboard.users.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('images', 'public') : null;
        $identityPath = $request->file('identity') ? $request->file('identity')->store('identities', 'public') : null;

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'balance' => $request->balance,
            'password' => Hash::make($request->password),
            'image' => $imagePath,
            'identity' => $identityPath,
            'address' => $request->address,
            'description' => $request->description,
            'status' => $request->status,
            'is_verified' => $request->is_verified,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $request->file('image')->store('images', 'public');
        }

        if ($request->hasFile('identity')) {
            if ($user->identity) {
                Storage::disk('public')->delete($user->identity);
            }
            $user->identity = $request->file('identity')->store('identities', 'public');
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'balance' => $request->balance,
            'image' => $user->image,
            'identity' => $user->identity,
            'address' => $request->address,
            'description' => $request->description,
            'status' => $request->status,
            'is_verified' => $request->is_verified,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }
        if ($user->identity) {
            Storage::disk('public')->delete($user->identity);
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

}
