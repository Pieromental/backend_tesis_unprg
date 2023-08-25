<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        try {
            //dd(JWTAuth::setToken($request->bearerToken())->getPayload()['sub']);
            $request["usuario"] = json_decode(JWTAuth::setToken($request->bearerToken())->getPayload()['sub']);
            //dd($request["usuario"]);
        } catch (JWTException  $e) {
            if (is_a($e, "Tymon\JWTAuth\Exceptions\TokenExpiredException") == 1) {
                $message = "token_expired";
            }else if (is_a($e, "Tymon\JWTAuth\Exceptions\TokenBlacklistedException") == 1) {
                $message = "token_blacklist";
            } else if (is_a($e, "Tymon\JWTAuth\Exceptions\TokenInvalidException") == 1) {
                $message = "token_invalid";
            } else {
                $message = "token_required";
            }
            return response()->json(array("code" => 401, "status" => false, "message" => $message), 401);
        }
        return $next($request);
    }
}
