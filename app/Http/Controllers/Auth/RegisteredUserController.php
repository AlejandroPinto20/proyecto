<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'ends_with:@est.unifranz.edu.bo,@docente.unifranz.edu.bo,@unifranz.edu.bo'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{8}$/', 'max:8'],
            'cod_est' => ['required', 'string', 'max:50', 'unique:'.User::class],
        ]);

        // Set default password to cod_est (now required)
        $defaultPassword = $request->cod_est;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => '+591' . $request->phone, // Add Bolivia country code
            'cod_est' => $request->cod_est,
            'password' => Hash::make($defaultPassword),
            'password_changed' => false, // New users haven't changed password yet
            'role' => 'estudiante', // Default role
            'is_active' => true,
        ]);

        // Send welcome notification with default password
        $user->notify(new WelcomeNotification($defaultPassword));

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
