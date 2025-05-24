<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Проверка роли администратора
        if ($role === 'Admin' && $user->role !== 'Admin') {
            abort(403, 'Доступ запрещен');
        }
        
        // Проверка роли заведующего складом (Manager)
        if ($role === 'Manager' && !in_array($user->role, ['Admin', 'Manager'])) {
            abort(403, 'Доступ запрещен');
        }
        
        // Проверка роли работника склада (Employee)
        if ($role === 'Employee' && !in_array($user->role, ['Admin', 'Manager', 'Employee'])) {
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}
