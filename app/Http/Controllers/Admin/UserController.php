<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => fn ($q) => $q->latest(), 'preference']);

        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,customer'],
        ]);

        // Don't let an admin accidentally demote their own only account
        // and lock themselves out — a small safety guard, not a spec item.
        if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
            return back()->with('status', "You can't remove your own admin role.");
        }

        $user->update($validated);

        return back()->with('status', 'User role updated.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($user->id === $request->user()->id, 422, "You can't delete your own account.");

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}
