<?php

namespace Tests\Feature\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;


class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_users_register(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);

        $response->assertStatus(201);
    }

    public function test_users_register_validation_email(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'test',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $response->assertStatus(302);
    }

    public function test_users_register_validation_name(): void
    {
        $response = $this->post(route('register'), [
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $response->assertStatus(302);
    }

    public function test_users_register_validation_password(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password_confirmation' => '123456',
        ]);

        $response->assertStatus(302);
    }

    public function test_users_register_validation_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456q',
        ]);

        $response->assertStatus(302);
    }

    public function test_users_register_validation_password_confirmation_none(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
    }

    public function test_users_register_exists(): void
    {
        $user = User::factory()->create(
            [
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => '123456',
            ]
        );
        $response = $this->post(route('register'), [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(302);

    }

    public function test_users_login(): void
    {
       $user = User::factory()->create([
           'name'  => 'test',
           'email' => 'test@gmail.com',
           'password' => '123456',
       ]);
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $response->assertStatus(200);
    }

    public function test_none_users_login(): void
    {

        $response = $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(401);
    }

    public function test_users_logout(): void
    {
        $user = User::factory()->create(
            [
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => '123456',
            ]
        );

       Sanctum::actingAs( $user, ['*']);

        $response = $this->get(route('logout'));

        $response->assertOk();
    }

//    public function test_redirect_to_google()
//    {
//        $response = $this->get(route('google.redirect'));
//
//        $response->assertRedirect();
//    }

//    public function test_handle_google_callback_new_user()
//    {
//        $googleUser = [
//            'id' => '123456',
//            'name' => 'Test User',
//            'email' => 'testuser@gmail.com',
//        ];
//
//        Socialite::shouldReceive('driver->stateless->user')
//                 ->once()
//                 ->andReturn((object) $googleUser); // Приведение к объекту
//
//        $this->assertDatabaseMissing('users', ['email' => $googleUser['email']]);
//
//        $response = $this->get(route('google.callback'));
//
//        $response->assertStatus(201);
//
//        $this->assertDatabaseHas('users', [
//            'email' => $googleUser['email'],
//            'google_id' => $googleUser['id'],
//        ]);
//    }

//    public function test_handle_google_callback_existing_user_with_google_id()
//    {
//        $user = User::factory()->create([
//            'google_id' => '123456',
//            'email' => 'testuser@gmail.com',
//        ]);
//
//        $googleUser = [
//            'id' => '123456',
//            'name' => 'Test User',
//            'email' => 'testuser@gmail.com',
//        ];
//
//        Socialite::shouldReceive('driver->stateless->user')
//                 ->once()
//                 ->andReturn((object) $googleUser);
//
//
//        $response = $this->get(route('google.callback'));
//
//        $response->assertStatus(200);
//    }
//
//
//    public function test_handle_google_callback_existing_user_without_google_id()
//    {
//        $user = User::factory()->create([
//            'email' => 'testuser@gmail.com',
//            'google_id' => null,
//        ]);
//
//        $googleUser = [
//            'id' => '123456',
//            'name' => 'Test User',
//            'email' => 'testuser@gmail.com',
//        ];
//
//        Socialite::shouldReceive('driver->stateless->user')
//                 ->once()
//                 ->andReturn((object) $googleUser);
//
//        $response = $this->get(route('google.callback'));
//
//        $response->assertStatus(200);
//
//        $this->assertDatabaseHas('users', [
//            'email' => 'testuser@gmail.com',
//            'google_id' => '123456',
//        ]);
//    }



}
