<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AccessLog;
use App\Notifications\PasswordChangeReminderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Log successful login
            AccessLog::logAccess('login', $user, ['email' => $request->email]);
            
            // Check if user needs to change password (first login or hasn't changed it)
            if (!$user->password_changed) {
                // Store current password info in session for display
                $defaultPassword = $user->cod_est ?? 'N/A';
                session(['show_password_change_prompt' => true, 'default_password' => $defaultPassword]);

                // Send password change reminder notification only if cod_est exists
                if ($user->cod_est) {
                    $user->notify(new PasswordChangeReminderNotification($defaultPassword));
                }

                return redirect(route('dashboard', absolute: false));
            }

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            // Log failed login attempt
            AccessLog::create([
                'user_id' => null,
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'failed_login',
                'route' => 'login',
                'request_data' => ['email' => $request->email],
                'success' => false,
                'session_id' => $request->session()->getId(),
                'created_at' => now()
            ]);
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout
        if ($user) {
            AccessLog::logAccess('logout', $user);
        }
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
