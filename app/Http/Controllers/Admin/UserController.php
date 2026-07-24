<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,suspended'],
        ]);

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function updateBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'balance_investissable' => ['required', 'numeric', 'min:0'],
            'balance_retirable' => ['required', 'numeric', 'min:0'],
        ]);

        $user->update($validated);

        return back()->with('success', 'User balance updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
    public function grantSpin(User $user)
    {
        $user->update(['lucky_wheel_available' => true]);
        return back()->with('success', "Spin granted to {$user->name}.");
    }
}
