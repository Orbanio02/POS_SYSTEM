<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * List payment methods
     */
    public function index()
    {
        $methods = PaymentMethod::orderBy('name')->get();

        return view('methods.index', compact('methods'));
    }

    /**
     * Store new payment method  ✅ FIXES YOUR ERROR
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
     * Enable / Disable payment method
     */
    public function toggle(PaymentMethod $method)
    {
        $method->update([
            'is_active' => ! $method->is_active,
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
