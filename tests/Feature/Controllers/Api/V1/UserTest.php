<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_current_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs( $user );

        $response = $this->get(route('user.index'));

        $response->assertStatus(200);
    }

    public function test_no_current_user(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.index'));

        $response->assertStatus(302);
    }

    public function test_remove_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );

        $this->assertDatabaseHas( 'users', [
            'id' => $user->id,
        ] );

        $response = $this->delete( route( 'user.destroy'));

        $this->assertDatabaseMissing('users',[
            'id' => $user->id,
        ]);

        $response->assertStatus( 204 );
    }

    public function test_user_update_name(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );

        $response = $this->put( route( 'user.update'), [
            'name'     => 'test',
        ]);

        $this->assertDatabaseHas( 'users', [
            'id' => $response->json( 'data.id' ),
            'name'     => 'test',
        ] );

        $response->assertStatus( 200 );
    }

    public function test_user_update_name_and_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );

        $response = $this->put( route( 'user.update'), [
            'name'     => 'test',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ]);

        $this->assertDatabaseHas( 'users', [
            'id' => $response->json( 'data.id' ),
            'name'     => 'test',
        ] );

        $response->assertStatus( 200 );
    }

    public function test_user_update_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );

        $response = $this->put( route( 'user.update'), [
            'name' => $user->name,
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ]);

        $this->assertDatabaseHas( 'users', [
            'id' => $response->json( 'data.id' ),
            'name' => $user->name,
        ] );

        $response->assertStatus( 200 );
    }
}
