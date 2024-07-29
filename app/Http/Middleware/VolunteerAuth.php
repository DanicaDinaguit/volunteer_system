<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VolunteerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('VolunteerAuth: Checking volunteer authentication.');
        // Check if the user is authenticated as a volunteer
        if (!Auth::guard('web')->check()) {
            return redirect()->route('volunteer.signIn')->with('error', 'Please sign in to access this page.');
        }

        return $next($request);
    }
}
