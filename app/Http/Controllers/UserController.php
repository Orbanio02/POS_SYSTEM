<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Only allow SUPERADMIN to see user list.
     * (Extra protection even if routes already restrict.)
     */
    public function index()
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $users = User::with('roles')->latest()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Create form — roles are filtered here
     */
    public function create()
    {
        $roles = $this->allowedRolesFor(Auth::user());

        abort_if($roles->isEmpty(), 403);

        return view('users.create', compact('roles'));
    }

    /**
     * Store user — role is validated AND enforced server-side
     */
    public function store(Request $request)
    {
        $roles = $this->allowedRolesFor(Auth::user());

        abort_if($roles->isEmpty(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        // ✅ HARD BLOCK: prevent assigning roles not allowed (even if HTML is edited)
        abort_unless($roles->contains($data['role']), 403);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        // If admin can't access users.index, redirect safely somewhere else
        if (!Auth::user()->hasRole('superadmin')) {
            return redirect()->route('dashboard')->with('success', 'User created successfully.');
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Edit form — roles are filtered here too
     */
    public function edit(User $user)
    {
        $authUser = Auth::user();

        // Admin should never be able to edit superadmin accounts
        if ($authUser->hasRole('admin') && $user->hasRole('superadmin')) {
            abort(403);
        }

        $roles = $this->allowedRolesFor($authUser);

        abort_if($roles->isEmpty(), 403);

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user — role is validated AND enforced server-side
     * ✅ New update: Superadmin can optionally reset/set user password.
     */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();

        // Admin should never be able to edit superadmin accounts
        if ($authUser->hasRole('admin') && $user->hasRole('superadmin')) {
            abort(403);
        }

        $roles = $this->allowedRolesFor($authUser);

        abort_if($roles->isEmpty(), 403);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
        ];

        // ✅ Only superadmin can set/reset password
        if ($authUser->hasRole('superadmin')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $data = $request->validate($rules);

        // ✅ HARD BLOCK: prevent role promotion / forbidden role changes
        abort_unless($roles->contains($data['role']), 403);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // ✅ Update password only if superadmin provided a new one
        if ($authUser->hasRole('superadmin') && !empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        $user->syncRoles([$data['role']]);

        // If admin can't access users.index, redirect safely somewhere else
        if (!$authUser->hasRole('superadmin')) {
            return redirect()->route('dashboard')->with('success', 'User updated successfully.');
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete user — keep as-is unless you want admin-only restrictions.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    /**
     * ✅ Role filtering logic
     * - superadmin => all roles
     * - admin => only client
     * - others => none
     */
    private function allowedRolesFor(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return Role::orderBy('name')->pluck('name');
        }

        if ($user->hasRole('admin')) {
            return Role::whereIn('name', ['client'])->orderBy('name')->pluck('name');
        }

        return collect();
    }
}
