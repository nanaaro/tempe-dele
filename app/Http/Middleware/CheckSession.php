<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (!Session::get('logged_in')) {
            // Kalau request AJAX/JSON, return 401 bukan redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect('/login');
        }
        return $next($request);
    }
}
