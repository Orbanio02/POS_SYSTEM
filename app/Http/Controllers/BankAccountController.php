<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    /**
     * Store new bank account (SUPERADMIN ONLY)
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $data = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:150',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:150',
        ]);

        $data['is_active'] = true;
        $data['created_by'] = Auth::id();

        BankAccount::create($data);

        return back()->with('success', 'Bank account added successfully.');
    }

    /**
     * ✅ UPDATE bank account (SUPERADMIN ONLY)
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $data = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:150',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:150',
        ]);

        $bankAccount->update($data);

        return back()->with('success', 'Bank account updated successfully.');
    }

    /**
     * Toggle active / inactive (SUPERADMIN ONLY)
     */
    public function toggle(BankAccount $bankAccount)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $bankAccount->update([
            'is_active' => !$bankAccount->is_active,
        ]);

        return back()->with('success', 'Bank account status updated.');
    }

    /**
     * Delete bank account (SUPERADMIN ONLY)
     */
    public function destroy(BankAccount $bankAccount)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $bankAccount->delete();

        return back()->with('success', 'Bank account deleted.');
    }
}
