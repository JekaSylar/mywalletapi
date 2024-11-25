<?php

namespace Tests\Feature\Controllers\Api\V1\Accounts;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase {
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_account_index(): void {
        $user = User::factory()->create();

        $this->actingAs( $user );

        $response = $this->get( route( 'accounts.index' ) );

        $response->assertStatus( 200 );
    }

    public function test_account_index_no_auth(): void {
        $response = $this->get( route( 'accounts.index' ) );

        $response->assertStatus( 302 );
    }

    public function test_account_store(): void {
        $user = User::factory()->create();
        $this->actingAs( $user );
        $response = $this->post( route( 'accounts.store' ), [
            'name'     => 'test',
            'balance'  => '100',
            'currency' => 'USD',
        ] );


        $this->assertDatabaseHas( 'accounts', [
            'id' => $response->json( 'data.id' ),
        ] );


        $response->assertStatus( 201 );
    }

    public function test_account_update(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );
        $account = Account::factory([
            'user_id' => $user->id,
            'name'    => 'test',
        ])->create();

        $response = $this->put( route( 'accounts.update', $account), [
            'name'     => 'test',
        ]);

        $this->assertDatabaseHas( 'accounts', [
            'id' => $response->json( 'data.id' ),
            'name'     => 'test',
            'user_id' => $user->id,
        ] );

        $response->assertStatus( 200 );
    }

    public function test_account_update_no_auth(): void
    {
        $userNotOwner = User::factory()->create();
        $user = User::factory()->create();
        $this->actingAs( $user );
        $account = Account::factory([
            'user_id' =>  $userNotOwner->id,
            'name'    => 'test',
        ])->create();

        $this->assertDatabaseHas( 'accounts', [
            'id' => $account->id,
        ] );


        $response = $this->put( route( 'accounts.update', $account), [
            'name'     => 'test',
        ]);

        $response->assertStatus( 403 );
    }

    public function test_account_delete(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );

        $account = Account::factory([
            'user_id' =>  $user->id,
            'name'    => 'test',
        ])->create();

        $this->assertDatabaseHas( 'accounts', [
            'id' => $account->id,
        ] );

        $response = $this->delete( route( 'accounts.destroy', $account));

        $this->assertDatabaseMissing('accounts',[
            'id' => $account->id,
        ]);

        $response->assertStatus( 204 );

    }

    public function test_account_delete_no_auth(): void
    {
        $user = User::factory()->create();
        $userNotOwner = User::factory()->create();
        $this->actingAs( $user );

        $account = Account::factory([
            'user_id' =>  $userNotOwner->id,
            'name'    => 'test',
        ])->create();

        $this->assertDatabaseHas( 'accounts', [
            'id' => $account->id,
        ] );

        $response = $this->delete( route( 'accounts.destroy', $account));


        $response->assertStatus( 403 );

    }
}
