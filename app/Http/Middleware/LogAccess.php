<?php

namespace App\Http\Middleware;

use App\Models\AccessLog;
use Closure;
use Illuminate\Http\Request;

class LogAccess
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Solo registrar ciertas rutas importantes
        $routesToLog = [
            'login',
            'logout',
            'admin.dashboard',
            'admin.users',
            'admin.activities',
            'admin.donations',
            'admin.certificates'
        ];
        
        $routeName = $request->route()?->getName();
        
        if ($routeName && in_array($routeName, $routesToLog)) {
            AccessLog::logAccess(
                $this->getActionFromRoute($routeName),
                auth()->user(),
                $this->getRelevantData($request)
            );
        }
        
        return $response;
    }
    
    private function getActionFromRoute(string $routeName): string
    {
        $actions = [
            'login' => 'login',
            'logout' => 'logout',
            'admin.dashboard' => 'admin_access',
            'admin.users' => 'user_management',
            'admin.activities' => 'activity_management',
            'admin.donations' => 'donation_management',
            'admin.certificates' => 'certificate_management'
        ];
        
        return $actions[$routeName] ?? 'system_access';
    }
    
    private function getRelevantData(Request $request): array
    {
        $data = [];
        
        if ($request->isMethod('POST')) {
            $data['method'] = 'POST';
            if ($request->has('email')) {
                $data['email'] = $request->input('email');
            }
        }
        
        return $data;
    }
}