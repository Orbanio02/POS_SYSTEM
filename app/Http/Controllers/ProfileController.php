<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->user()->update(
            $request->validate([
                'name'  => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
            ])
        );

        return back()->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        // ✅ SUPERADMIN ONLY (SERVER SIDE SECURITY)
        abort_unless($request->user()->hasRole('superadmin'), 403);

        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        return redirect('/');
    }
}
