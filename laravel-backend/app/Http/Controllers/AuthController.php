<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {

        if ($request->has('username') && $request->has('password')) {

            $user = User::query()
                ->where('username', $request->get('username'))
                ->where('password', $request->get('password'))
                ->first();

            if($user) {

                return response()->json([
                    'code' => 200,
                    'user_id' => $user->id,
                    'username' => $user->username,
                ]);

            }
            else {
                throw new \Exception(__('auth.error.notfound'), 403);
            }

        }
        else {
            throw new \Exception(__('auth.error.null'), 401);
        }

    }
}
