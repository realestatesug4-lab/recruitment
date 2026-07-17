<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $adminCode = config('app.admin_registration_code');
        if (filled($adminCode) && $request->input('admin_code') !== $adminCode) {
            throw ValidationException::withMessages([
                'admin_code' => 'The admin registration code is invalid.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        if (method_exists($user, 'assignRole') && class_exists(Role::class)) {
            $role = Role::findByName('admin');
            if ($role) {
                $user->assignRole($role);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended('/admin');
    }
}
