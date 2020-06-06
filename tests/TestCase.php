<?php

namespace Tests;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Exceptions\JWTException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $user;

    public function setUp():void
    {
        parent::setUp();
    }
    /**
 * Return request headers needed to interact with the API.
 *
 * @return Array array of headers.
 */
    protected function headers($user = null)
    {

        $credentials = ['email' => $user->email, 'password' => 'foo'];
        try {
            // verify the credentials and create a token for the user
            if (! $token = auth('api')->attempt($credentials) ) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $headers = ['Accept' => 'application/json'];
        $headers['Authorization'] = 'Bearer '.$token;
        return $headers;
    }
}
