<?php

namespace App\Api\V1\Controllers;

use App\Discipline;
use Illuminate\Http\Request;
use App\Group;
use App\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;

class LoginController extends Controller
{
    /**
     * Log the user in
     *
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            $token = Auth::guard()->attempt($credentials);

            if (!$token) {
                throw new AccessDeniedHttpException();
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        $user = User::where('email', '=', $credentials['email'])->first();

        if ($user->is_teacher) {
            $disciplines = Discipline::where('teacher_id', '=', $user->id)->get();
            return response()
                ->json([
                    'status' => 'ok',
                    'token' => $token,
                    'user' => $user,
                    'disciplines' => $disciplines,
                    'expires_in' => Auth::guard()->factory()->getTTL() * 60
                ]);
        }

        $group = Group::find($user->group_id);
        $disciplines = $group->discipline()->get();

        return response()
            ->json([
                'status' => 'ok',
                'token' => $token,
                'user' => $user,
                'group' => $group,
                'disciplines' => $disciplines,
                'expires_in' => Auth::guard()->factory()->getTTL() * 60
            ]);
    }

    public function groups()
    {
        $groups = Group::all();
        return response()
            ->json(['groups' => $groups]);
    }
}
