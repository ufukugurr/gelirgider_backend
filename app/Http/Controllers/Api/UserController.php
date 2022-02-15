<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

class UserController extends Controller
{
    /**
     * User registration in the application after validation.
     *
     * @param  App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $user = User::create($request->all());
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'token' => $user->createToken('accessToken')->accessToken,
            ];
            return sendResponse($data);
        } catch (Exception $e) {
            return sendError('Beklenmeyen bir hata oluştu.', 500);
        }
    }

    /**
     * User login in the application after validation.
     *
     * @param  App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'token' => $user->createToken('accessToken')->accessToken,
            ];
            return sendResponse($data);
        } else {
            return sendError('E-posta veya şifre yanlış.', 401);
        }
    }
}
