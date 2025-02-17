<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if (!Auth::check() || Auth::user()->role !== $role) {
            $user = Auth::user();

            if ($user) {
                $role = $user->role;
        
                switch ($role) {
                    case 'Teacher':
                        return redirect()->route('teacher-home');
                    case 'Student':
                        return redirect()->route('student-home');
                    default:
                        return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }
}
