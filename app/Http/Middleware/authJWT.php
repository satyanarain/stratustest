<?php

namespace App\Http\Middleware;
use JWTAuth;
use Exception;
use Closure;
use Session;
use Tymon\JWTAuth\Middleware\BaseMiddleware;


class authJWT extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    const HEADER_TOKEN = 'x-access-token';

    public function handle($request, Closure $next)
    {
        $token = $request->header(self::HEADER_TOKEN);
        if (!$token) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['error'=>'Token is Expired']);

            }else{

                return response()->json(['error'=>'Something is wrong']);

            }

        }

        if (!$user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }
        
        $request->request->add(['u_id' => $user->id]);
        $request->request->add(['u_username' => $user->username]);
        $request->request->add(['u_email' => $user->email]);
        $request->request->add(['u_first_name' => $user->first_name]);
        $request->request->add(['u_last_name' => $user->u_last_name]);
        $request->request->add(['u_company_name' => $user->company_name]);
        $request->request->add(['u_phone_number' => $user->phone_number]);
        $request->request->add(['u_status' => $user->status]);
        $request->request->add(['u_role' => $user->role]);
        $this->events->fire('tymon.jwt.valid', $user);
        return $next($request);
    }
}