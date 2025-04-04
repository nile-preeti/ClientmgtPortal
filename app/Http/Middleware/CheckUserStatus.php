<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
    
            // Skip status check for admin users
            if ($user->role_id == 1) {
                return $next($request);
            }
    
            // If non-admin and deactivated
            if ($user->status == 0) {
                Auth::logout();
    
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your account has been deactivated by the admin.',
                    ], 401);
                }
    
                return redirect()->route('user.login')->withErrors([
                    'message' => 'Your account has been deactivated by the admin.',
                ]);
            }
        }
    
        return $next($request);
    }
}
