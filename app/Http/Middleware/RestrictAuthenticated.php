<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role) {

            switch ($user->role) {
                case 'Teacher':
                    return redirect()->route('teacher-home');
                case 'Student':
                    return redirect()->route('student-home');
                default:
                    return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
