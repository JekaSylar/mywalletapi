<?php

namespace Controllers\Api\V1;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_categories_index(): void {
        $user = User::factory()->create();

        $this->actingAs( $user );

        $response = $this->get( route( 'category.index' ) );

        $response->assertStatus( 200 );
    }

    public function test_categories_index_no_auth(): void {
        $response = $this->get( route( 'category.index' ) );

        $response->assertStatus( 302 );
    }

    public function test_category_store(): void {
        $user = User::factory()->create();
        $this->actingAs( $user );
        $response = $this->post( route( 'category.store' ), [
            'name'     => 'test',
        ] );


        $this->assertDatabaseHas( 'categories', [
            'id' => $response->json( 'data.id' ),
        ] );


        $response->assertStatus( 201 );
    }

    public function test_category_update(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );
        $category = Category::factory([
            'user_id' => $user->id,
            'name'    => 'test',
        ])->create();

        $response = $this->put( route( 'category.update', $category), [
            'name'     => 'test',
        ]);

        $this->assertDatabaseHas( 'categories', [
            'id' => $response->json( 'data.id' ),
            'name'     => 'test',
            'user_id' => $user->id,
        ] );

        $response->assertStatus( 200 );
    }

    public function test_category_update_no_auth(): void
    {
        $userNotOwner = User::factory()->create();
        $user = User::factory()->create();
        $this->actingAs( $user );
        $account = Category::factory([
            'user_id' =>  $userNotOwner->id,
            'name'    => 'test',
        ])->create();

        $this->assertDatabaseHas( 'categories', [
            'id' => $account->id,
        ] );


        $response = $this->put( route( 'category.update', $account), [
            'name'     => 'test',
        ]);

        $response->assertStatus( 403 );
    }

    public function test_category_delete(): void
    {
        $user = User::factory()->create();
        $this->actingAs( $user );

        $category = Category::factory([
            'user_id' =>  $user->id,
            'name'    => 'test',
        ])->create();

        $this->assertDatabaseHas( 'categories', [
            'id' => $category->id,
        ] );

        $response = $this->delete( route( 'category.destroy', $category));

        $this->assertDatabaseMissing('accounts',[
            'id' => $category->id,
        ]);

        $response->assertStatus( 204 );

    }

    public function test_category_delete_no_auth(): void
    {
        $user = User::factory()->create();
        $userNotOwner = User::factory()->create();
        $this->actingAs( $user );

        $category = Category::factory([
            'user_id' =>  $userNotOwner->id,
            'name'    => 'test',
        ])->create();

        $this->assertDatabaseHas( 'categories', [
            'id' => $category->id,
        ] );

        $response = $this->delete( route( 'category.destroy', $category));


        $response->assertStatus( 403 );

    }

}
