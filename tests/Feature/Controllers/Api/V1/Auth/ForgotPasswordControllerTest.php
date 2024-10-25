<?php

namespace Tests\Feature\Controllers\Api\V1\Auth;

use App\Models\ResetCodePassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase {
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_send_code_mail(): void {
        $user = User::factory()->create( [
            'email'    => 'testuser@gmail.com',
            'name'     => 'Test User',
            'password' => 'password',
        ] );

        $this->assertDatabaseHas( 'users', [
            'email' => $user->email,
        ] );

        $response = $this->post( route( 'password-forgot.send-code-mail' ), [
            'email' => $user->email,
        ] );

        $code = str_pad( rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );

        $user->resetCode()->create( [
            'code'       => $code,
            'token'      => Str::random( 32 ),
            'expires_at' => Carbon::now()->addHour()
        ] );

        $this->assertDatabaseHas( 'reset_code_passwords', [
            'user_id' => $user->id,
        ] );


        $response->assertStatus( 200 );
    }

    public function test_send_code_mail_none_user(): void {
        $this->assertDatabaseMissing( 'users', [
            'email' => 'testuser@gmail.com',
        ] );

        $response = $this->post( route( 'password-forgot.send-code-mail' ), [
            'email' => 'testuser@gmail.com',
        ] );


        $response->assertStatus( 404 );
    }

    public function test_code_check(): void {
        $user = User::factory()->create( [
            'email'    => 'testuser@gmail.com',
            'name'     => 'Test User',
            'password' => 'password',
        ] );
        $this->assertDatabaseHas( 'users', [ 'email' => $user->email ] );

        $reset_code = ResetCodePassword::factory()->create( [
            'user_id'    => $user->id,
            'code'       => str_pad( rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT ),
            'token'      => Str::random( 32 ),
            'expires_at' => Carbon::now()->addHour()
        ] );

        $this->assertDatabaseHas( 'reset_code_passwords', [ 'id' => $reset_code->id ] );

        $response = $this->post( route( 'password-forgot.code.check' ), [
            'token' => $reset_code->token,
            'code'  => $reset_code->code,
        ] );

        $response->assertStatus( 200 );
    }

    public function test_code_check_old_hour(): void {
        $user = User::factory()->create( [
            'email'    => 'testuser@gmail.com',
            'name'     => 'Test User',
            'password' => 'password',
        ] );
        $this->assertDatabaseHas( 'users', [ 'email' => $user->email ] );

        $reset_code = ResetCodePassword::factory()->create( [
            'user_id'    => $user->id,
            'code'       => str_pad( rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT ),
            'token'      => Str::random( 32 ),
            'expires_at' => Carbon::now()->subHour()
        ] );

        $this->assertDatabaseHas( 'reset_code_passwords', [ 'id' => $reset_code->id ] );

        $response = $this->post( route( 'password-forgot.code.check' ), [
            'token' => $reset_code->token,
            'code'  => $reset_code->code,
        ] );

        $response->assertStatus( 410 );
    }

    public function test_code_check_not(): void {
        $response = $this->post( route( 'password-forgot.code.check' ), [
            'token' => Str::random( 32 ),
            'code'  => Str::random( 8 )
        ] );


        $response->assertStatus( 404 );
    }

    public function test_update_password(): void {
        $user = User::factory()->create( [
            'email'    => 'testuser@gmail.com',
            'name'     => 'Test User',
            'password' => 'password',
        ] );

        $this->assertDatabaseHas( 'users', [
            'email' => $user->email,
        ] );


        $code = str_pad( rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );

        $password_reset = $user->resetCode()->create( [
            'code'       => $code,
            'token'      => Str::random( 32 ),
            'expires_at' => Carbon::now()->addHour()
        ] );

        $this->assertDatabaseHas( 'reset_code_passwords', [
            'user_id' => $user->id,
        ] );

        $response = $this->post( route( 'password-forgot.update' ), [
            'token'                 => $password_reset->token,
            'password'              => 'password',
            'password_confirmation' => 'password'
        ] );


        $response->assertStatus( 200 );
    }

    public function test_update_password_not_token(): void
    {
        $token = Str::random( 32 );
        $this->assertDatabaseMissing( 'reset_code_passwords', [
            'token' => $token ,
        ] );

        $response = $this->post( route( 'password-forgot.update' ), [
            'token'                 => $token,
            'password'              => 'password',
            'password_confirmation' => 'password'
        ] );

        $response->assertStatus( 404 );
    }
}
