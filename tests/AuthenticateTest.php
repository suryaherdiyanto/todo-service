<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthenticateTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A test for authenticate user for getting access token
     *
     * @return void
     */
    public function testAuthenticateUser()
    {
        $user = factory(App\User::class)->create();
        $user->profile()->create(factory(App\Profile::class)->make()->toArray());

        $this->json('POST', '/api/user/auth', ['email' => $user->email, 'password' => '123123'])
            ->seeJson([
                'status' => 'ok'
            ]);
        $this->seeStatusCode(200);
    }

    public function testGetAuthenticated()
    {
        $user = factory(App\User::class)->create();
        $user->profile()->create(factory(App\Profile::class)->make()->toArray());

        $this->actingAs($user)->json('get', '/api/user/me')
            ->seeJsonEquals([
                'user' => [
                    'data' => $user->toArray(),
                    'profile' => $user->profile
                ]
            ]);
        $this->seeStatusCode(200);
    }

    public function testRegisterUser()
    {
        $user = factory(App\User::class)->make();

        $this->json('post', '/api/user/register', ['email' => $user->email, 'password' => '123123', 'password_confirmation' => '123123'])
            ->seeJson([
                'status' => 'ok'
            ]);
        $this->seeInDatabase('users', $user->toArray());
        $this->seeStatusCode(201);
    }

    public function testRegisterValidation()
    {
        $data = [
            'email' => '123',
            'password' => '123123'
        ];

        $this->json('post', '/api/user/register', $data)
            ->seeJson([
                'message' => 'Error sending request'
            ])
            ->seeJsonStructure([
                'debug',
                'errors' => [
                    'email',
                    'password'
                ],
                'message',
                'status_code'
            ]);
        $this->seeStatusCode(422);
    }

    // public function testLogout()
    // {
    //     $this->user = factory(App\User::class)->create();
    //     $this->user->profile()->create(factory(App\Profile::class)->make()->toArray());

    //     $this->actingAs($this->user)->json('post', '/api/user/logout')
    //         ->seeJson([
    //             'status' => 'ok'
    //         ]);
    // }
}