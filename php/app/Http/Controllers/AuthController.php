<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    public function register(Request $request) {
        $request->validate([
            'login' => 'required|unique:users|min:4',
            'password' => 'required|confirmed|min:6'
        ], [
            'login.required' => 'Поле \'Логин\' обязательное.',
            'login.unique' => 'Логин уже занят.',
            'login.min' => 'Минимальная длина логина 4.',
            'password.required' => 'Поле \'Пароль\' обязательное.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.min' => 'Минимальная длина пароля 6.'
        ]);
        $user = User::create([
            'login' => $request->login,
            'password' => bcrypt($request->password)
        ]);

        $token = JWTAuth::fromUser($user);
        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        request()->validate([
            'login' => 'required',
            'password' => 'required'
        ], [
            'login.required' => 'Поле \'Логин\' обязательное.',
            'password.required' => 'Поле \'Пароль\' обязательное.'
        ]);
        $credentials = request(['login', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
