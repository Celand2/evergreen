<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

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

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(8);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return back()
            ->with('success', "Password reset for {$user->name}.")
            ->with('generated_password', $newPassword);
    }
    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "Password updated for {$user->name}.");
    }
}
