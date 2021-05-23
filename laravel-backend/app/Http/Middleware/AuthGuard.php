<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AuthGuard
{

    /**
     * Handle an incoming request.
     *
     * This middleware is checking the simple user's credentials.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->headers->has('x-auth-user') &&
            $request->headers->get('x-auth-user') !== null
        ) {

            $user = User::query()
                ->where('id', $request->headers->get('x-auth-user'))
                ->first();

            if($user) {
                $request->merge(['user_id' => $user->id]);
            }
            else {
                throw new \Exception(__('auth.error.forbidden'), 401);
            }

        }
        else {
            throw new \Exception(__('auth.error.required'), 401);
        }

        return $next($request);
    }
}
