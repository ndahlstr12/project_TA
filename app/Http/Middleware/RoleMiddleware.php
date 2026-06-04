<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $roles = explode('|', $role);
        $hasAccess = false;
        
        foreach ($roles as $r) {
            if ($request->user() && $request->user()->hasRole($r)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            // Redirect ke dashboard masing-masing jika tidak punya akses
            $user = $request->user();
            $target = '/login';
            
            if ($user) {
                if ($user->role === 'admin') $target = '/admin/dashboard';
                elseif ($user->role === 'walikelas') $target = '/class-teacher/dashboard';
                elseif ($user->role === 'guru') $target = '/teacher/dashboard';
                elseif ($user->role === 'siswa') $target = '/student/dashboard';
                elseif ($user->role === 'orangtua') $target = '/parent/dashboard';
            }

            return redirect($target)->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
