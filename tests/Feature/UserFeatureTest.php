<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory('App\Models\User')->create(['password' => bcrypt('foo')]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function user_can_register_itself()
    {

        // make users
        $user = factory('App\Models\User')->make();
        $data = $user->toArray();
        $data['password_confirmation'] = $user->password;
        //dd($data);
        $response = $this->post('api/register', $data);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function user_can_login_and_recieve_token(){

         // make users and register
         $user = factory('App\Models\User')->make();
         $data = $user->toArray();
         $data['password_confirmation'] = $user->password;
         $this->post('api/register', $data);

        // login
        $this->post('api/login', $user->toArray())
        ->assertStatus(200)
        ->assertJson([
            'token_type' => 'bearer',
        ]);
    }

    /**
     * @test
     */
    public function listing_all_users_with_search_term_or_without(){

        //dd($this->headers($this->user));
        $this->get('api/user/',[],$this->headers($this->user))
            ->assertStatus(200);

    }
}
