<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
class CheckTokenValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'code' => 403,
                'status' => false,
                'message' => 'Token expired',
                'data' => null,
            ], 403);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'code' => 403,
                'status' => false,
                'message' => 'Token invalid',
                'data' => null,
            ], 403);
        } catch (JWTException $e) {
            return response()->json([
                'code' => 403,
                'status' => false,
                'message' => 'Token not provided',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
