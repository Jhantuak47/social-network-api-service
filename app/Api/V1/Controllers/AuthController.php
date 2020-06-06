<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\UserLoginRequest;
use App\Api\V1\Requests\UserRegisterRequest;
use App\Api\V1\Resources\UserListResource;
use App\Models\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
             'email'    => $request->email,
             'password' => bcrypt($request->password),
             'name' => $request->name,
         ]);

        return $this->response()->noContent();
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        if(JWTAuth::getToken()){
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(['access_token'=> $token, 'token_type'=> 'bearer']);
        }else{
            throw new StoreResourceFailedException('Invalid Credential, Please Login!');
        }
        
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        return new UserListResource($user);
    }

}