<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * SUPERADMIN ONLY
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $search = $request->input('search');

        $users = User::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->get()
            ->sortBy(function ($user) {
                $role = $user->roles->pluck('name')->first();

                return match ($role) {
                    'superadmin' => 1,
                    'admin' => 2,
                    'client' => 3,
                    default => 4,
                };
            });

        return view('users.index', compact('users', 'search'));
    }

    /**
     * ADMIN + SUPERADMIN
     * Create client accounts
     */
    public function create()
    {
        $roles = $this->allowedRolesFor(Auth::user());
        abort_if($roles->isEmpty(), 403);

        $products = Auth::user()->hasAnyRole(['superadmin', 'admin'])
            ? Product::orderBy('name')->get()
            : collect();

        return view('users.create', compact('roles', 'products'));
    }

    /**
     * STORE USER
     * Admin + Superadmin
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
            'products' => 'array',
            'products.*' => 'exists:products,id',
        ]);

        abort_unless($roles->contains($data['role']), 403);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        if (
            Auth::user()->hasAnyRole(['superadmin', 'admin']) &&
            $data['role'] === 'client'
        ) {
            $sync = collect($data['products'] ?? [])
                ->mapWithKeys(fn($id) => [
                    $id => ['assigned_by' => Auth::id()]
                ])
                ->toArray();

            $user->products()->sync($sync);
        }

        return Auth::user()->hasRole('superadmin')
            ? redirect()->route('users.index')->with('success', 'User created successfully.')
            : redirect()->route('dashboard')->with('success', 'Client created successfully.');
    }

    /**
     * EDIT — SUPERADMIN ONLY
     */
    public function edit(User $user)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $roles = $this->allowedRolesFor(Auth::user());

        $products = $user->hasRole('client')
            ? Product::orderBy('name')->get()
            : collect();

        return view('users.edit', compact('user', 'roles', 'products'));
    }

    /**
     * UPDATE — SUPERADMIN ONLY
     */
    public function update(Request $request, User $user)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
            'products' => 'array',
            'products.*' => 'exists:products,id',
        ];

        $data = $request->validate($rules);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            ...(!empty($data['password'])
                ? ['password' => Hash::make($data['password'])]
                : []),
        ]);

        $user->syncRoles([$data['role']]);

        if ($user->hasRole('client')) {
            $sync = collect($data['products'] ?? [])
                ->mapWithKeys(fn($id) => [
                    $id => ['assigned_by' => Auth::id()]
                ])
                ->toArray();

            $user->products()->sync($sync);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * DELETE USER — SUPERADMIN ONLY
     */
    public function destroy(User $user)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        \DB::table('product_user')
            ->where('assigned_by', $user->id)
            ->update(['assigned_by' => null]);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * ROLE FILTERING
     */
    private function allowedRolesFor(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return Role::orderBy('name')->pluck('name');
        }

        if ($user->hasRole('admin')) {
            return Role::whereIn('name', ['client'])->pluck('name');
        }

        return collect();
    }
}
