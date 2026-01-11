<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    /**
     * List payment methods + bank accounts
     */
    public function index()
    {
        $methods = PaymentMethod::orderBy('name')->get();
        $bankAccounts = BankAccount::orderBy('bank_name')->get();

        return view('methods.index', compact('methods', 'bankAccounts'));
    }

    /**
     * Store new payment method
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:payment_methods,name',
        ]);

        PaymentMethod::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()
            ->route('methods.index')
            ->with('success', 'Payment method added successfully.');
    }

    /**
     * EDIT payment method (SUPERADMIN ONLY)
     */
    public function edit(PaymentMethod $method)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        return view('methods.edit', compact('method'));
    }

    /**
     * UPDATE payment method (SUPERADMIN ONLY)
     */
    public function update(Request $request, PaymentMethod $method)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $data = $request->validate([
            'name' => 'required|string|max:50|unique:payment_methods,name,' . $method->id,
        ]);

        $method->update([
            'name' => $data['name'],
        ]);

        return redirect()
            ->route('methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Update payment instructions (SUPERADMIN ONLY)
     */
    public function updateInstructions(Request $request, PaymentMethod $method)
    {
        abort_unless(Auth::user()->hasRole('superadmin'), 403);

        $data = $request->validate([
            'instructions' => 'nullable|string|max:5000',
        ]);

        $method->update([
            'instructions' => $data['instructions'],
        ]);

        return back()->with('success', 'Instructions updated successfully.');
    }

    /**
     * Enable / Disable payment method
     */
    public function toggle(PaymentMethod $method)
    {
        $method->update([
            'is_active' => !$method->is_active,
        ]);

        return back()->with('success', 'Payment method status updated.');
    }

    /**
     * Delete payment method
     */
    public function destroy(PaymentMethod $method)
    {
        $method->delete();

        return back()->with('success', 'Payment method deleted.');
    }
}
