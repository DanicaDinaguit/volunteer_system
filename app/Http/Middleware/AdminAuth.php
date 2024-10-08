<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('AdminAuthMiddleware: Checking admin authentication.');
        
        if (!Auth::guard('admin')->check()) {
            Log::warning('AdminAuthMiddleware: Unauthenticated access attempt.');
            return redirect()->route('admin.signIn')->with('error', 'Please sign in as admin to access this page.');
        }
        
        return $next($request);
        
        
    }
}