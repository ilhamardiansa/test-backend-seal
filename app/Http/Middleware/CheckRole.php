<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$roles): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'code' => 401,
                'status' => false,
                'message' => 'Silakan login dahulu',
                'data' => null,
            ], 401);
        }
    
        $user = Auth::user();
        $rolesArray = explode('|', $roles);
    
        foreach ($rolesArray as $role) {
            if ($user->level == $role) {
                return $next($request);
            }
        }
    
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => 'Tidak memiliki role yang sesuai',
            'data' => null,
        ], 403);
    }
}
